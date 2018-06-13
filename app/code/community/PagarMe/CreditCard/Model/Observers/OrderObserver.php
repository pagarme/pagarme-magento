<?php

class PagarMe_CreditCard_Model_Observers_OrderObserver
{

    /**
     * @param Varien_Event_Observer $observer
     */
    public function changeStatus(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order->getCapture()) {
            $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true)->save();
        }
    }
}
