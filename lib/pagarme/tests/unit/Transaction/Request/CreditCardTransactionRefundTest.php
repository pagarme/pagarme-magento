<?php

namespace PagarMe\SdkTest\Transaction\Request;

use PagarMe\Sdk\Transaction\Request\CreditCardTransactionRefund;
use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\RequestInterface;

class CreditCardTransactionRefundTest extends \PHPUnit_Framework_TestCase
{
    const PATH           = 'transactions/1337/refund';
    const TRANSACTION_ID = 1337;

    public function refundAmountProvider()
    {
        return [
            [null],
            [1000],
            [300],
            [5050]
        ];
    }

    /**
     * @dataProvider refundAmountProvider
     * @test
     */
    public function mustContentBeCorrect($amount)
    {
        $transactionCreate = new CreditCardTransactionRefund(
            $this->getTransactionMock(),
            $amount
        );

        $this->assertEquals(
            [
                'amount' => $amount
            ],
            $transactionCreate->getPayload()
        );

        $this->assertEquals(
            sprintf(self::PATH, self::TRANSACTION_ID),
            $transactionCreate->getPath()
        );

        $this->assertEquals(RequestInterface::HTTP_POST, $transactionCreate->getMethod());
    }

    private function getTransactionMock()
    {
        $transactionMock = $this->getMockBuilder('PagarMe\Sdk\Transaction\CreditCardTransaction')
            ->disableOriginalConstructor()
            ->getMock();

        $transactionMock->method('getId')->willReturn(self::TRANSACTION_ID);

        return $transactionMock;
    }
}
