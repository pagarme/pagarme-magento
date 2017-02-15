<?php

namespace PagarMe\Sdk\Balance;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Balance\Request\BalanceGet;

class BalanceHandler extends AbstractHandler
{
    /**
     * @return array
     */
    public function get()
    {
        $request = new BalanceGet();

        $response = $this->client->send($request);

        return new Balance(get_object_vars($response));
    }
}
