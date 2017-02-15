<?php

namespace PagarMe\Sdk\BalanceOperation;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\BankAccount\BankAccount;
use PagarMe\Sdk\BalanceOperation\Request\BalanceOperationList;
use PagarMe\Sdk\BalanceOperation\Request\BalanceOperationGet;
use PagarMe\Sdk\BalanceOperation\Operation;
use PagarMe\Sdk\BalanceOperation\Movement;

class BalanceOperationHandler extends AbstractHandler
{
    use \PagarMe\Sdk\BalanceOperation\OperationBuilder;

    /**
     * @param int $page
     * @param int $count
     * @param string $status
     * @return array
     */
    public function getList($page = null, $count = null, $status = null)
    {
        $request = new BalanceOperationList($page, $count, $status);

        $response = $this->client->send($request);
        $operations = [];

        foreach ($response as $operationData) {
            $operations[] = $this->buildOperation($operationData);
        }

        return $operations;
    }

    /**
     * @param int $balanceOperationId
     * @return Operation
     */
    public function get($balanceOperationId)
    {
        $request = new BalanceOperationGet($balanceOperationId);

        $response = $this->client->send($request);

        return $this->buildOperation($response);
    }
}
