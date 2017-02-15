<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Transaction\CreditCardTransaction;

class CreditCardTransactionRefund implements RequestInterface
{
    /**
     * @var CreditCardTransaction
     */
    protected $transaction;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @param CreditCardTransaction $transaction
     * @param int $amount
     */
    public function __construct(CreditCardTransaction $transaction, $amount)
    {
        $this->transaction = $transaction;
        $this->amount      = $amount;
    }

    /**
     * @param string
     */
    public function getPayload()
    {
        return [
            'amount' => $this->amount
        ];
    }

    /**
     * @param string
     */
    public function getPath()
    {
        return sprintf('transactions/%d/refund', $this->transaction->getId());
    }

    /**
     * @param string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
