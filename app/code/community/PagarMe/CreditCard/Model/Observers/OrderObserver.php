<?php

use \PagarMe\Sdk\Transaction\AbstractTransaction;

/**
 * @deprecated
 */
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
            $this->isAuthorizeAndCapture($order) &&
            $pagarmeTransaction->isPaid()
        ) {
            $this->createInvoice($order);
        }

        if (
            $this->isAuthorizeAndCapture($order) &&
            $pagarmeTransaction->isRefused()
        ) {
            $this->setStateForRefusedTransaction($order);
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

    /**
     * Returns true if the payment_action is authorize and capture
     *
     * @param \Mage_Sales_Model_Order $order
     * @return bool
     */
    private function isAuthorizeAndCapture($order)
    {
        return $order->getCapture() === 'authorize_capture';
    }

    /**
     * Update and insert a feedback about why an order is refused by gateway
     *
     * @param \Mage_Sales_Model_Order $order
     * @return void
     */
    private function setStateForRefusedTransaction($order)
    {
        $transactionRefusedReason = $order
            ->getPagarmeTransaction()
            ->getRefuseReason();

        $refusedMessage = 'Refused by gateway.';

        if ($transactionRefusedReason === 'acquirer') {
            $refusedMessage .= ' Transaction unauthorized';
        }

        if ($transactionRefusedReason === 'antifraud') {
            $refusedMessage .= ' Suspected fraud';
        }

        $createCommentHistory = true;

        $order->setState(
            Mage_Sales_Model_Order::STATE_CANCELED,
            $createCommentHistory,
            Mage::helper('pagarme_core')->__($refusedMessage)
        );
    }
}
