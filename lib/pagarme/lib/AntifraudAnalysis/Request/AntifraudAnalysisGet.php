<?php

namespace PagarMe\Sdk\AntifraudAnalysis\Request;

use PagarMe\Sdk\RequestInterface;

class AntifraudAnalysisGet implements RequestInterface
{
    /**
     * @var PagarMe\Sdk\Transaction\AbstractTransaction
     */
    private $transaction;

    /**
     * @var int
     */
    private $antifraudAnalysis;

    /**
     * @param PagarMe\Sdk\Transaction\AbstractTransaction $transaction
     */
    public function __construct($transaction, $antifraudAnalysis)
    {
        $this->transaction       = $transaction;
        $this->antifraudAnalysis = $antifraudAnalysis;
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
            'transactions/%d/antifraud_analyses/%d',
            $this->transaction->getId(),
            $this->antifraudAnalysis
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
