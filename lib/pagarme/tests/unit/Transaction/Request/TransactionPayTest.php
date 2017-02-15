<?php

namespace PagarMe\SdkTest\Transaction\Request;

use PagarMe\Sdk\Transaction\Request\TransactionPay;
use PagarMe\Sdk\RequestInterface;

class TransactionPayTest extends \PHPUnit_Framework_TestCase
{
    const PATH           = 'transactions/1337';
    const TRANSACTION_ID = 1337;

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $transactionMock = $this->getMockBuilder('PagarMe\Sdk\Transaction\BoletoTransaction')
            ->disableOriginalConstructor()
            ->getMock();
        $transactionMock->method('getId')
            ->willReturn(self::TRANSACTION_ID);

        $transactionCreate = new TransactionPay($transactionMock);

        $this->assertEquals(
            [
                'status' => 'paid'
            ],
            $transactionCreate->getPayload()
        );
        $this->assertEquals(self::PATH, $transactionCreate->getPath());
        $this->assertEquals(RequestInterface::HTTP_PUT, $transactionCreate->getMethod());
    }
}
