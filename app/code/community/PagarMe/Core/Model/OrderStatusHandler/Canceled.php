<?php

use PagarMe_Core_Model_OrderStatusHandler_Base as BaseHandler;

class PagarMe_Core_Model_OrderStatusHandler_Canceled extends BaseHandler
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
     * Cancel an order with custom message
     *
     * @throws \Mage_Core_Exception
     */
    private function cancel()
    {
        if ($this->order->canCancel()) {
            $refuseMessage = Mage::helper('pagarme_core')
                ->__($this->buildRefusedReasonMessage());

            $this->order->getPayment()->cancel();
            $this->order->registerCancellation($refuseMessage);

            Mage::dispatchEvent(
                'order_cancel_after',
                ['order' => $this->order]
            );
        }
    }

    /**
     * Responsible to handle order status based on transaction status
     */
    public function handleStatus()
    {
        $magentoTransaction = Mage::getModel(
            'core/resource_transaction'
        );

        $this->cancel();

        $magentoTransaction->addObject($this->order)->save();

        return $this->order;
    }
}
