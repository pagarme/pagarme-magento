<?php

class PagarMe_Core_Model_PostbackHandler_Refused extends PagarMe_Core_Model_PostbackHandler_Base
{
    const MAGENTO_DESIRED_STATE = Mage_Sales_Model_Order::STATE_CANCELED;

    /**
     * Returns the desired state on magento
     *
     * @return string
     */
    protected function getDesiredState()
    {
        return self::MAGENTO_DESIRED_STATE;
    }

    /**
     * Cancel an order with custom message
     *
     * @throws \Mage_Core_Exception
     */
    private function cancel()
    {
        if ($this->order->canCancel()) {
            $this->order->getPayment()->cancel();
            $this->order->registerCancellation(
                Mage::helper('pagarme_core')->__('Refused by gateway.')
            );

            Mage::dispatchEvent(
                'order_cancel_after',
                ['order' => $this->order]
            );
        }
    }

    /**
     * @return \Mage_Sales_Model_Order
     */
    public function process()
    {
        $transaction = Mage::getModel('core/resource_transaction');

        $this->cancel();

        $transaction->addObject($this->order)->save();

        return $this->order;
    }
}