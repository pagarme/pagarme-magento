<?php

namespace PagarMe\Sdk\AntifraudAnalysis\Request;

use PagarMe\Sdk\RequestInterface;

class AntifraudAnalysisList implements RequestInterface
{
    /**
     * @var PagarMe\Sdk\Transaction\AbstractTransaction
     */
    private $transaction;

    /**
     * @param PagarMe\Sdk\Transaction\AbstractTransaction $transaction
     */
    public function __construct($transaction)
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
            'transactions/%d/antifraud_analyses',
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
