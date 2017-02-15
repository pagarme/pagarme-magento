<?php

namespace PagarMe\Sdk\Payable\Request;

use PagarMe\Sdk\RequestInterface;

class PayableGet implements RequestInterface
{
    /**
     * @var int
     */
    private $payableId;

    /**
     * @param int $payableId
     */
    public function __construct($payableId)
    {
        $this->payableId = $payableId;
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
        return sprintf('payables/%s', $this->payableId);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
