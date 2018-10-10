<?php

use PagarMe_CreditCard_Model_Installments as Installments;

// @codingStandardsIgnoreLine
class PagarMe_CreditCard_Model_Quote_Address_Total_CreditCardInterestAmount extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    use PagarMe_Core_Trait_ConfigurationsAccessor;

    /**
     * @var float
     */
    private $interestValue;

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return bool
     */
    private function shouldCollect(Mage_Sales_Model_Quote_Address $address)
    {
        return $this->paymentMethodUsedWasPagarme($address) &&
            $this->addressUsedIsShipping($address) &&
            $this->wasCalledAfterPaymentMethodSelection() &&
            $this->interestRateIsntZero() &&
            $this->paymentIsntInterestFree();
    }

    /**
     * The class is called for the two addresses (billing and shipping)
     * This prevents the method from adding the interest two times
     *
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return PagarMe_CreditCard_Model_Quote_Address_Total_CreditCardInterestAmount
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        if ($this->shouldCollect($address)) {
            $paymentMethodParameters = Mage::app()
                ->getRequest()
                ->getPost()['payment'];

            $this->interestValue = $this->interestAmountInReals(
                $address,
                $paymentMethodParameters
            );

            $this->_addAmount($this->interestValue);
            $this->_addBaseAmount($this->interestValue);

            $orderTotal = $address->getGrandTotal() + $this->interestValue;
            $address->setGrandTotal($orderTotal);
            $address->setBaseGrandTotal($orderTotal);
        }

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return PagarMe_CreditCard_Model_Quote_Address_Total_CreditCardInterestAmount
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        // @codingStandardsIgnoreLine
        if ($this->shouldCollect($address)) {
            $address->addTotal([
                'code' => $this->getCode(),
                'title' => __('Installments related Interest'),
                'value' => $this->interestValue
            ]);
        }

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @param array $paymentMethodParameters
     *
     * @return float
     */
    private function interestAmountInReals($address, $paymentMethodParameters)
    {
        $pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk();

        $helper = Mage::helper('pagarme_core');

        $choosedInstallments = $paymentMethodParameters['installments'];
        $totalAmountInCents = $helper->parseAmountToCents(
            $address->getBaseGrandTotal()
        );

        $installmentCalc = new Installments(
            $totalAmountInCents,
            $choosedInstallments,
            $this->getFreeInstallmentStoreConfig(),
            $this->getInterestRateStoreConfig(),
            $this->getMaxInstallmentStoreConfig(),
            $pagarMeSdk
        );

        // @codingStandardsIgnoreLine
        $interestAmountInCents = $installmentCalc->getTotal() - $totalAmountInCents;

        return $helper->parseAmountToCurrency($interestAmountInCents);
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return bool
     */
    private function paymentMethodUsedWasPagarme(
        Mage_Sales_Model_Quote_Address $address
    ) {
        $quote = $address->getQuote();
        return $quote->getPayment()->getMethod() == 'pagarme_creditcard';
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     *
     * @return bool
     */
    private function addressUsedIsShipping(
        Mage_Sales_Model_Quote_Address $address
    ) {
        return $address->getAddressType() == 'shipping';
    }

    /**
     * @return bool
     */
    private function wasCalledAfterPaymentMethodSelection()
    {
        $paymentMethodParameters = Mage::app()->getRequest()->getPost();

        // @codingStandardsIgnoreStart
        return array_key_exists('payment', $paymentMethodParameters)
            && array_key_exists('installments', $paymentMethodParameters['payment']);
        // @codingStandardsIgnoreEnd
    }

    /**
     * @return bool
     */
    private function interestRateIsntZero()
    {
        return $this->getFreeInstallmentStoreConfig() > 0;
    }

    /**
     * @return bool
     */
    private function paymentIsntInterestFree()
    {
        $paymentMethodParameters = Mage::app()->getRequest()->getPost();
        $installments = !$this->wasCalledAfterPaymentMethodSelection() ?
            -1 :
            $paymentMethodParameters['payment']['installments'];
        return $installments > $this->getFreeInstallmentStoreConfig();
    }
}
