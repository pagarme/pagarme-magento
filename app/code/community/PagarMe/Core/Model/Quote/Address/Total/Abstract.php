<?php

abstract class PagarMe_Core_Model_Quote_Address_Total_Abstract
 extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $transaction;

    /**
     * @return bool
     */
    protected function shouldCollect()
    {
        $paymentData = Mage::app()
            ->getRequest()
            ->getPost('payment');

        if (is_null($paymentData)) {
            return false;
        }

        if (!isset($paymentData['pagarme_modal_token'])) {
            return false;
        }

        if (empty($paymentData['pagarme_modal_token'])) {
            return false;
        }

        return true;
    }

    /**
     * @param $quote Mage_Sales_Model_Quote
     * @return double
     */
    protected function getSubtotal($quote)
    {
        $quoteTotals = $quote->getTotals();
        $baseSubtotalWithDiscount = $quoteTotals['subtotal']->getValue();

        $shippingAmount = $quote->getShippingAddress()->getShippingAmount();

        return $baseSubtotalWithDiscount + $shippingAmount;
    }

    /**
     * @return PagarMe\Sdk\PagarMe\AbstractTransaction
     */
    protected function getTransaction()
    {
        if ($this->transaction == null) {
            $this->transaction = Mage::app()
                ->getHelper('pagarme_modal')
                ->getTransaction();
        }

        return $this->transaction;
    }
}
