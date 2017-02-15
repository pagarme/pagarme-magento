<?php

namespace PagarMe\Sdk\Company;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Company\Request\CompanyInfo;

class CompanyHandler extends AbstractHandler
{
    /**
     * @return array
     */
    public function info()
    {
        $request = new CompanyInfo();

        $response = $this->client->send($request);

        return $response;
    }
}
