<?php

class PagarMe_Core_Model_PostbackHandler_Refunded extends PagarMe_Core_Model_PostbackHandler_Base
{
    /**
     * Returns the desired state on magento
     *
     * @return string
     */
    protected function getDesiredState()
    {
        return $this->order->getStatus();
    }

    /**
     * @return \Mage_Sales_Model_Order
     */
    public function process()
    {
        $orderService = Mage::getModel('sales/service_order', $this->order);

        $invoices = [];
        foreach ($this->order->getInvoiceCollection() as $invoice) {
            if ($invoice->canRefund()) {
                $invoices[] = $invoice;
            }
        }

        $transaction = Mage::getModel('core/resource_transaction');

        foreach ($invoices as $invoice) {
            $creditmemo = $orderService->prepareInvoiceCreditmemo($invoice);
            $creditmemo->setRefundRequested(true);
            $creditmemo->setOfflineRequested(true);
            $creditmemo->setPaymentRefundDisallowed(true)->register();
            $transaction->addObject($creditmemo);
        }
        $transaction->addObject($this->order)->save();

        return $this->order;
    }
}