<?php

use PagarMe_Core_Model_OrderStatusHandler_Base as BaseHandler;

class PagarMe_Core_Model_OrderStatusHandler_RefusedTransaction extends BaseHandler
{
    /**
     * Returns refuse message sent by Pagar.me API
     * @return string
     */
    private function buildRefusedReasonMessage()
    {
        return sprintf(
            'Refused by %s', $this->transaction->getRefuseReason()
        );
    }

    /**
     * Responsible to handle order status based on transaction status
     */
    public function handleStatus()
    {
        $canceledHandler = new PagarMe_Core_Model_OrderStatusHandler_Canceled(
            $this->order,
            $this->transaction,
            $this->buildRefusedReasonMessage()
        );

        $canceledHandler->handleStatus();

        return $this->order;
    }
}
