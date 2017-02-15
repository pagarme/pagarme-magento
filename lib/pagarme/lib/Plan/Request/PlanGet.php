<?php

namespace PagarMe\Sdk\Plan\Request;

use PagarMe\Sdk\RequestInterface;

class PlanGet implements RequestInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        return sprintf('plans/%d', $this->id);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
