<?php

use \PagarMe\Sdk\Transaction\AbstractTransaction;

class PagarMe_Core_Model_PostbackHandler_Factory
{
    /**
     * Instantiate a PostbackHandler based on desired status
     *
     * @param string $status
     * @param Mage_Sales_Model_Order $order
     * @param int $transactionId
     *
     * @return PagarMe_Core_Model_PostbackHandler_Base
     * @throws \Exception
     */
    public static function createFromDesiredStatus(
        $status,
        $order,
        $transactionId
    ) {
        if ($status === AbstractTransaction::PAID) {
            return new PagarMe_Core_Model_PostbackHandler_Paid(
                $order,
                $transactionId
            );
        }

        throw new \Exception(sprintf(
            'There\'s no postback handler for this desired status: %s',
            $status
        ));
    }
}