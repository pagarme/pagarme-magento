<?php

namespace PagarMe\Sdk\Balance\Request;

use PagarMe\Sdk\RequestInterface;

class BalanceGet implements RequestInterface
{
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
        return 'balance';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
