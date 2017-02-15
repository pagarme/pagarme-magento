<?php

namespace PagarMe\Sdk\Payable;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Payable\Request\PayableGet;
use PagarMe\Sdk\Payable\Request\PayableList;

class PayableHandler extends AbstractHandler
{
    use PayableBuilder;

    /**
     * @param int $payableId
     * @return Payable
     */
    public function get($payableId)
    {
        $request = new PayableGet($payableId);

        $response = $this->client->send($request);

        return $this->buildPayable($response);
    }

    /**
     * @param int $page
     * @param int $count
     */
    public function getList($page = null, $count = null)
    {
        $request = new PayableList($page, $count);

        $response = $this->client->send($request);

        $payables = [];

        foreach ($response as $payableData) {
            $payables[] = $this->buildPayable($payableData);
        }

        return $payables;
    }
}
