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
     * @return \Mage_Sales_Model_Order
     */
    public function process()
    {
        $transaction = Mage::getModel('core/resource_transaction');

        $this->order->setState(
            Mage_Sales_Model_Order::STATE_CANCELED,
            true,
            Mage::helper('pagarme_core')->
            __('Refused by gateway.')
        );

        $transaction->addObject($this->order)->save();

        return $this->order;
    }
}