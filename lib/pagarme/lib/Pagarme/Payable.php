<?php

class PagarMe_Payable extends PagarMe_Model
{
    const ENDPOINT_TRANSACTIONS = '/transactions';
    const ENDPOINT_PAYABLES = '/payables';

    /**
     * @param $transactionId
     * @return mixed
     * @throws Exception
     * @throws PagarMe_Exception
     */
    public static function findAllByTransactionId($transactionId)
    {
        $request = new PagarMe_Request(
            self::ENDPOINT_TRANSACTIONS . '/' . $transactionId . self::ENDPOINT_PAYABLES, 'GET'
        );

        $response = $request->run();
        $class = get_called_class();
        return new $class($response);
    }

    /**
     * @param $transactionId
     * @param $payableId
     * @return mixed
     * @throws Exception
     * @throws PagarMe_Exception
     */
    public static function findTrasactionById($transactionId, $payableId)
    {
        $request = new PagarMe_Request(
            self::ENDPOINT_TRANSACTIONS . '/' . $transactionId . self::ENDPOINT_PAYABLES . '/' . $payableId, 'GET'
        );

        $response = $request->run();
        $class = get_called_class();
        return new $class($response);
    }
}