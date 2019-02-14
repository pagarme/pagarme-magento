<?php

class PagarMe_CreditCard_Model_InvoiceTotals extends  Mage_Sales_Model_Order_Invoice_Total_Abstract
{

    /**
     * @param Mage_Sales_Model_Order_Invoice $invoice
     *
     * @return PagarMe_CreditCard_Model_InvoiceTotals
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $transaction = \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $order->getId()
            );
        $invoice->setGrandTotal(
            $invoice->getGrandTotal() + $transaction->getRateAmount()
        );
        $invoice->setBaseGrandTotal(
            $invoice->getBaseGrandTotal() + $transaction->getRateAmount()
        );

        return $this;
    }
}
