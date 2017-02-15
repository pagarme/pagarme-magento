<?php

namespace PagarMe\Sdk\Zipcode\Request;

use PagarMe\Sdk\RequestInterface;

class ZipcodeInfoGet implements RequestInterface
{
    /**
     * @var string
     */
    private $zipcode;

    /**
     * @param string $zipcode
     */
    public function __construct($zipcode)
    {
        $this->zipcode = $zipcode;
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
        return sprintf('zipcodes/%s', $this->zipcode);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
