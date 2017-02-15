<?php

namespace PagarMe\SdkTests\Recipient;

use PagarMe\Sdk\Recipient\Request\RecipientBalance;
use PagarMe\Sdk\RequestInterface;

class RecipientBalanceTest extends \PHPUnit_Framework_TestCase
{
    const ID     = 're_x1y2z3';
    const PATH   = 'recipients/re_x1y2z3/balance';

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::ID);

        $recipientBalance = new RecipientBalance($recipientMock);

        $this->assertEquals(self::PATH, $recipientBalance->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::ID);

        $recipientBalance = new RecipientBalance($recipientMock);

        $this->assertEquals(RequestInterface::HTTP_GET, $recipientBalance->getMethod());
    }

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::ID);

        $recipientBalance = new RecipientBalance($recipientMock);

        $this->assertEquals(
            [],
            $recipientBalance->getPayload()
        );
    }
}
