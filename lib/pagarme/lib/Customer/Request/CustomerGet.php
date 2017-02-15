<?php

namespace PagarMe\Sdk\Customer\Request;

use PagarMe\Sdk\RequestInterface;

class CustomerGet implements RequestInterface
{
    /**
     * @var int | Identificador do cliente
     */
    private $customerId;

    /**
     * @param int
     */
    public function __construct($customerId)
    {
        $this->customerId  = $customerId;
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
        return sprintf('customers/%d', $this->customerId);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
