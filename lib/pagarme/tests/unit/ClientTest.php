<?php

namespace PagarMe\SdkTest;

use GuzzleHttp\Client as GuzzleClient;
use PagarMe\Sdk\Client;
use PagarMe\Sdk\RequestInterface;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    const REQUEST_PATH   = 'test';
    const CONTENT        = 'sample content';
    const API_KEY        = 'myApiKey';

    private $guzzleClientMock;
    private $requestMock;

    public function setup()
    {
        $this->guzzleClientMock = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder('PagarMe\Sdk\RequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock->method('getMethod')->willReturn(RequestInterface::HTTP_POST);
        $this->requestMock->method('getPath')->willReturn(self::REQUEST_PATH);
        $this->requestMock->method('getPayload')->willReturn(
            ['content' => self::CONTENT]
        );
    }

    /**
     * @test
     */
    public function mustSendRequest()
    {
        $this->guzzleClientMock->expects($this->once())
            ->method('createRequest')
            ->willReturn($this->getMock('GuzzleHttp\Message\RequestInterface'));

        $responseMock = $this->getMockBuilder('GuzzleHttp\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $streamMock = $this->getMockBuilder('GuzzleHttp\Stream\Stream')
            ->disableOriginalConstructor()
            ->getMock();

        $responseMock->method('getBody')
            ->willReturn($streamMock);

        $this->guzzleClientMock->expects($this->once())->method('send')
            ->willReturn($responseMock);

        $client = new Client(
            $this->guzzleClientMock,
            self::API_KEY
        );

        $client->send($this->requestMock);
    }

    /**
     * @test
     */
    public function mustSendRequestWithProperContent()
    {
        $this->guzzleClientMock->expects($this->once())
            ->method('createRequest')
            ->with(
                RequestInterface::HTTP_POST,
                self::REQUEST_PATH,
                [
                    'json' => [
                        'content'        => self::CONTENT,
                        'api_key'        => self::API_KEY
                    ]
                ]
            )
            ->willReturn($this->getMock('GuzzleHttp\Message\RequestInterface'));

        $responseMock = $this->getMockBuilder('GuzzleHttp\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $streamMock = $this->getMockBuilder('GuzzleHttp\Stream\Stream')
            ->disableOriginalConstructor()
            ->getMock();

        $responseMock->method('getBody')
            ->willReturn($streamMock);

        $this->guzzleClientMock->expects($this->once())->method('send')
            ->willReturn($responseMock);

        $client = new Client(
            $this->guzzleClientMock,
            self::API_KEY
        );

        $client->send($this->requestMock);
    }

    /**
    * @expectedException PagarMe\Sdk\ClientException
    * @test
     */
    public function mustReturnClientExeptionWhenGetRequestException()
    {
        $guzzleRequestMock = $this->getMock('GuzzleHttp\Message\RequestInterface');

        $this->guzzleClientMock->expects($this->once())
            ->method('createRequest')
            ->willReturn($guzzleRequestMock);
        $this->guzzleClientMock->method('send')
            ->will(
                $this->throwException(
                    new \GuzzleHttp\Exception\RequestException(
                        'Exception',
                        $guzzleRequestMock
                    )
                )
            );
        $this->guzzleClientMock->expects($this->once())->method('send');

        $client = new Client(
            $this->guzzleClientMock,
            self::API_KEY
        );
        $client->send($this->requestMock);
    }

    /**
     * @test
     */
    public function mustSetDefaultTimeout()
    {
        $defaultTimeout = 144;

        $this->guzzleClientMock
            ->expects($this->once())
            ->method('setDefaultOption')
            ->with(
                $this->equalTo('timeout'),
                $this->equalTo($defaultTimeout)
            );

        $client = new Client(
            $this->guzzleClientMock,
            self::API_KEY
        );

        $client->setDefaultTimeout($defaultTimeout);
    }

    /**
     * @test
     */
    public function mustCreateWithDefaultTimeout()
    {
        $defaultTimeout = 132;

        $guzzleClient = new GuzzleClient();

        $client = new Client(
            $guzzleClient,
            self::API_KEY,
            $defaultTimeout
        );

        $this->assertEquals($defaultTimeout, $guzzleClient->getDefaultOption('timeout'));
        $this->assertEquals($guzzleClient->getDefaultOption('timeout'), $client->getDefaultTimeout());
    }
}
