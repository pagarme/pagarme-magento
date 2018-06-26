<?php

use \PagarMe\Sdk\Transaction\AbstractTransaction;

class PagarMe_CreditCard_Model_Observers_OrderObserver
{

    /**
     * @param Varien_Event_Observer $observer
     */
    public function changeStatus(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $pagarmeTransaction = $order->getPagarmeTransaction();
        if (!$pagarmeTransaction instanceof AbstractTransaction) {
            return;
        }
        if (
            $order->getCapture() === 'authorize_capture' &&
            $pagarmeTransaction->isPaid()
        ) {
          $this->createInvoice($order);
        }
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    protected function createInvoice($order)
    {
        $invoice = Mage::getModel('sales/service_order', $order)
            ->prepareInvoice();

        $invoice->setBaseGrandTotal($order->getGrandTotal());
        $invoice->setGrandTotal($order->getGrandTotal());
        $invoice->setInterestAmount($order->getInterestAmount());
        $invoice->register()->pay();
        $invoice->setTransactionId(
            $order->getPagarmeTransaction()->getId()
        );

        $order->setState(
            Mage_Sales_Model_Order::STATE_PROCESSING,
            true,
            "pago"
        );

        Mage::getModel('core/resource_transaction')
            ->addObject($order)
            ->addObject($invoice)
            ->save();
    }
}
