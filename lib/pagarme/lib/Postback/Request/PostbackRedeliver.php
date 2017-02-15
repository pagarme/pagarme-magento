<?php

namespace PagarMe\Sdk\Postback\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Transaction\AbstractTransaction;

class PostbackRedeliver implements RequestInterface
{
    /**
     * @var AbstractTransaction
     */
    protected $transaction;

    /**
     * @var string
     */
    protected $postbackId;

    /**
     * @param AbstractTransaction $transaction
     * @param string $postbackId
     */
    public function __construct(AbstractTransaction $transaction, $postbackId)
    {
        $this->transaction = $transaction;
        $this->postbackId = $postbackId;
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
            'transactions/%d/postbacks/%s/redeliver',
            $this->transaction->getId(),
            $this->postbackId
        );
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
