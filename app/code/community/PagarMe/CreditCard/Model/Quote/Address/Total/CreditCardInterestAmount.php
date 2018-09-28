<?php

use PagarMe_Core_Model_CurrentOrder as CurrentOrder;

class PagarMe_CreditCard_Model_Quote_Address_Total_CreditCardInterestAmount
    extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    use PagarMe_Core_Trait_ConfigurationsAccessor;

    private $interestValue;

    /**
     * The class is called for the two addresses (billing and shipping)
     * This prevents the method from adding the interest two times
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        if (
            $this->paymentMethodUsedWasPagarme($address) &&
            $this->addressUsedIsShipping($address) &&
            $this->wasCalledAfterPaymentMethodSelection() &&
            $this->interestRateIsntZero() &&
            $this->paymentIsntInterestFree()
        ) {
            $paymentMethodParameters = Mage::app()->getRequest()->getPost()['payment'];
            $address->setDiscountAmount(0);
            $address->setBaseDiscountAmount(0);
            $this->interestValue = $this->interestAmountInReals(
                Mage::getSingleton('checkout/session')->getQuote(),
                $paymentMethodParameters
            );

            $this->_addAmount($this->interestValue);
            $this->_addBaseAmount($this->interestValue);
        }

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if (
            $this->paymentMethodUsedWasPagarme($address) &&
            $this->addressUsedIsShipping($address) &&
            $this->wasCalledAfterPaymentMethodSelection() &&
            $this->interestRateIsntZero() &&
            $this->paymentIsntInterestFree()
        ) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => __('Installments related Interest'),
                'value' => $this->interestValue
            ));
        }

        return $this;
    }

    private function interestAmountInReals($quote, $paymentMethodParameters)
    {
        $pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter');
        $currentQuote = new CurrentOrder($quote, $pagarMeSdk);
        $calculedInstallments = $currentQuote->calculateInstallments(
            $this->getMaxInstallmentStoreConfig(),
            $this->getFreeInstallmentStoreConfig(),
            $this->getInterestRateStoreConfig()
        );

        $choosedInstallmentsValue = $paymentMethodParameters['installments'];
        $installmentsInfo = $calculedInstallments[$choosedInstallmentsValue];
        $valueWithInterestInCents = $installmentsInfo['total_amount'];

        $helper = Mage::helper('pagarme_core');
        $interestInCents = $valueWithInterestInCents - $currentQuote
            ->productsTotalValueInCents();
        return $helper->parseAmountToFloat($interestInCents);
    }

    private function paymentMethodUsedWasPagarme(
        Mage_Sales_Model_Quote_Address $address
    ) {
        $quote = $address->getQuote();
        return $quote->getPayment()->getMethod() == 'pagarme_creditcard';
    }

    private function addressUsedIsShipping(
        Mage_Sales_Model_Quote_Address $address
    ) {
        return $address->getAddressType() == 'shipping';
    }

    private function wasCalledAfterPaymentMethodSelection()
    {
        $paymentMethodParameters = Mage::app()->getRequest()->getPost();
        return array_key_exists('payment', $paymentMethodParameters)
            && array_key_exists('installments', $paymentMethodParameters['payment']);
    }

    private function interestRateIsntZero()
    {
        return $this->getFreeInstallmentStoreConfig() > 0;
    }

    private function paymentIsntInterestFree()
    {
        $paymentMethodParameters = Mage::app()->getRequest()->getPost();
        $installments = !$this->wasCalledAfterPaymentMethodSelection() ?
            -1 :
            $paymentMethodParameters['payment']['installments'];
        return $installments > $this->getFreeInstallmentStoreConfig();
    }
}
