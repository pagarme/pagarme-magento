<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Transaction\AbstractTransaction;

class PostbackTransactionList implements RequestInterface
{
    /**
     * @var AbstractTransaction
     */
    protected $transaction;

    /**
     * @param AbstractTransaction $transaction
     */
    public function __construct(AbstractTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf(
            'transactions/%d/postbacks',
            $this->transaction->getId()
        );
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
