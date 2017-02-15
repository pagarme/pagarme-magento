<?php

namespace PagarMe\Sdk\Company\Request;

use PagarMe\Sdk\RequestInterface;

class CompanyInfo implements RequestInterface
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
        return 'company';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
