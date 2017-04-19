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

        if (!isset($paymentData['pagarme_checkout_token'])) {
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
     * @param $token string
     * @return PagarMe\Sdk\PagarMe\AbstractTransaction
     */
    protected function getTransaction()
    {
        if ($this->transaction == null) {
            $paymentData = Mage::app()
                ->getRequest()
                ->getPost('payment');

            $this->transaction = Mage::getModel(
                'pagarme_core/sdk_adapter'
            )->getPagarMeSdk()
            ->transaction()
            ->get($paymentData['pagarme_checkout_token']);
        }

        return $this->transaction;
    }
}