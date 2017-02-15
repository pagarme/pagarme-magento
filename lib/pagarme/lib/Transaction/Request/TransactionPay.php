<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Transaction\BoletoTransaction;

class TransactionPay implements RequestInterface
{
    /**
     * @var BoletoTransaction
     */
    protected $transaction;

    /**
     * @param BoletoTransaction $transaction
     */
    public function __construct(BoletoTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'status' => 'paid'
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf('transactions/%d', $this->transaction->getId());
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_PUT;
    }
}
