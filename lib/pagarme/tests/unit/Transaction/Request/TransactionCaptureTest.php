<?php

namespace PagarMe\SdkTest\Transaction\Request;

use PagarMe\Sdk\Transaction\Request\TransactionCapture;
use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\RequestInterface;

class TransactionCaptureTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'transactions/%s/capture';

    public function transactionCaptureProvider()
    {
        return [
            [555, null , []],
            [273690, 500 , ['amount'   => 500]],
            [888888, 76500 , ['amount' => 76500]]
        ];
    }

    /**
     * @dataProvider transactionCaptureProvider
     * @test
     */
    public function mustPayloadBeCorrect($transactionId, $amount, $payload)
    {
        $transactionCreate = new TransactionCapture($transactionId, $amount);

        $this->assertEquals(
            $payload,
            $transactionCreate->getPayload()
        );

        $this->assertEquals(
            sprintf(self::PATH, $transactionId),
            $transactionCreate->getPath()
        );

        $this->assertEquals(RequestInterface::HTTP_POST, $transactionCreate->getMethod());
    }
}
