<?php

namespace PagarMe\Sdk\BalanceOperation\Request;

use PagarMe\Sdk\RequestInterface;

class BalanceOperationGet implements RequestInterface
{
    /**
     * @var int
     */
    private $balanceOperationId;

    /**
     * @param int $balanceOperationId
     */
    public function __construct($balanceOperationId)
    {
        $this->balanceOperationId = $balanceOperationId;
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
        return sprintf('balance/operations/%d', $this->balanceOperationId);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
