<?php

namespace PagarMe\Sdk\Zipcode;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Zipcode\Request\ZipcodeInfoGet;

class ZipcodeHandler extends AbstractHandler
{
    /**
     * @param string $zipcode
     * @return array
     */
    public function getInfo($zipcode)
    {
        $request = new ZipcodeInfoGet($zipcode);

        return $this->client->send($request);
    }
}
