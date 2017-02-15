<?php

namespace PagarMe\Sdk\Postback;

use PagarMe\Sdk\Postback\PostbackHandler;

class PostbackHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function mustCheckValidPostback()
    {
        $clientMock = $this->getMockBuilder('PagarMe\Sdk\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->method('getApiKey')
            ->willReturn('ak_test_pagarme');

        $postbackHandler = new PostbackHandler($clientMock);

        $this->assertTrue(
            $postbackHandler->validateRequest(
                'data=value&random=postback&any=content',
                'sha1=0c533b2efaccae059e741a50babd3e3dd51d466b'
            )
        );
    }

    /**
     * @test
     */
    public function mustCheckNonValidPostback()
    {
        $clientMock = $this->getMockBuilder('PagarMe\Sdk\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->method('getApiKey')
            ->willReturn('ak_test_pagarme');

        $postbackHandler = new PostbackHandler($clientMock);

        $this->assertFalse(
            $postbackHandler->validateRequest(
                'data=false&random=postback&any=content',
                'sha1=0c533b2efaccae059e741a50babd3e3dd51d466b'
            )
        );
    }
}
