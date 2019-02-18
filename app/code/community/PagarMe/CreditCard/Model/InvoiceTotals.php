<?php

use PagarMe_CreditCard_Model_Installments as Installments;

class PagarMe_CreditCard_Model_InvoiceTotals extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    use PagarMe_Core_Trait_ConfigurationsAccessor;

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /**
     * @param Mage_Sales_Model_Order_Invoice $invoice
     *
     * @return PagarMe_CreditCard_Model_InvoiceTotals
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $this->order = $invoice->getOrder();

        $transaction = \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $this->order->getId()
            );

        if ($this->shouldUpdateRateAmount($transaction)) {
            $transaction->setRateAmount(
                $this->updateRateAmount($transaction, $invoice)
            );
        }

        $invoice->setGrandTotal(
            $invoice->getGrandTotal() + $transaction->getRateAmount()
        );
        $invoice->setBaseGrandTotal(
            $invoice->getBaseGrandTotal() + $transaction->getRateAmount()
        );

        return $this;
    }

    /**
     * @param PagarMe_Core_Model_Transaction $transaction
     * @param Mage_Sales_Model_Order_Invoice $invoice
     *
     * @return float
     */
    private function updateRateAmount(
        PagarMe_Core_Model_Transaction $transaction,
        Mage_Sales_Model_Order_Invoice $invoice
    ) {
        $sdk = Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk();

        $orderTotal =
            $invoice->getGrandTotal() + $this->order->getShippingAmount();

        $installments = new Installments(
            Mage::helper('pagarme_core')
                ->parseAmountToCents($orderTotal),
            $transaction->getInstallments(),
            $this->getFreeInstallmentStoreConfig(),
            $transaction->getInterestRate(),
            $this->getMaxInstallmentStoreConfig(),
            $sdk
        );

        $updatedRateAmount = Mage::helper('pagarme_core')
            ->parseAmountToCurrency($installments->getRateAmount());

        $writeConnection = Mage::getSingleton('core/resource')
            ->getConnection('core_write');

        $updateRateAmountQuery = sprintf(
            'UPDATE pagarme_transaction SET %s',
            'rate_amount = :rateAmount WHERE order_id = :orderId;'
        );

        $queryValues = [
            'rateAmount' => $updatedRateAmount,
            'orderId' => $this->order->getId()
        ];

        $writeConnection->query($updateRateAmountQuery, $queryValues);

        return $updatedRateAmount;
    }

    /**
     * @param PagarMe_Core_Model_Transaction $transaction
     *
     * @return bool
     */
    private function shouldUpdateRateAmount(
        PagarMe_Core_Model_Transaction $transaction
    ) {
        return (bool)$transaction->getInterestRate();
    }
}
