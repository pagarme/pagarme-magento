<?php

class PagarMe_Core_Model_PostbackHandler_Authorized extends PagarMe_Core_Model_PostbackHandler_Base
{
    /**
     * Given a authorize postback the desired status on magento is
     * pending_payment
     */
    const MAGENTO_DESIRED_STATUS = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;

    /**
     * Returns the desired state on magento
     *
     * @return string
     */
    protected function getDesiredState()
    {
        return self::MAGENTO_DESIRED_STATUS;
    }

    /**
     * @return \Mage_Sales_Model_Order
     * @throws PagarMe_Core_Model_PostbackHandler_Exception
     */
    public function process()
    {
        $authorizedAmount = substr(
            'R$'.$this->order->getGrandTotal(),
            0,
            -2
        );
        $this->order->setState(
            self::MAGENTO_DESIRED_STATUS,
            true,
            Mage::helper('sales')->__(
                'Authorized amount of %s.',
                $authorizedAmount
            )
        );

        Mage::getModel('core/resource_transaction')
            ->addObject($this->order)
            ->save();

        return $this->order;
    }
}
