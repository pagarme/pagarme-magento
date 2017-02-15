<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\RequestInterface;

class TransactionCapture implements RequestInterface
{
    /**
     * @var int
     */
    protected $transactionId;
    /**
     * @var int
     */
    protected $amount;

    /**
     * @param int $transaction
     * @param int $amount
     */
    public function __construct($transactionId, $amount)
    {
        $this->transactionId = $transactionId;
        $this->amount = $amount;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        if (is_null($this->amount)) {
            return [];
        }
        return [
            'amount' => $this->amount
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf('transactions/%d/capture', $this->transactionId);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
