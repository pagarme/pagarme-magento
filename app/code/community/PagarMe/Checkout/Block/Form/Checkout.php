<?php

class PagarMe_Checkout_Block_Form_Checkout extends Mage_Payment_Block_Form
{
    const TEMPLATE = 'pagarme/form/checkout.phtml';

    /**
     * @var Mage_Sales_Model_Quote
     */
    private $quote;

    /**
     * @var Mage_Customer_Model_Customer
     */
    private $customer;

    /**
     * @codeCoverageIgnore
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate(self::TEMPLATE);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getEncryptionKey()
    {
        return Mage::getStoreConfig('payment/pagarme_settings/encryption_key');
    }

    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getButtonText()
    {
        return Mage::getStoreConfig(
            'payment/pagarme_settings/checkout_button_text'
        );
    }

    /**
     * @codeCoverageIgnore
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        if (is_null($this->quote)) {
            $this->quote = Mage::getSingleton('checkout/session')->getQuote();
        }

        return $this->quote;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param Mage_Sales_Model_Quote $quote Current quote from session
     * @return void
     */
    public function setQuote(Mage_Sales_Model_Quote $quote)
    {
        $this->quote = $quote;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        if (is_null($this->customer)) {
            $this->customer = Mage::getSingleton('customer/session')
                ->getCustomer();
        }

        return $this->customer;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param Mage_Customer_Model_Customer $customer Current customer
     *                                               from session
     * @return void
     */
    public function setCustomer(Mage_Customer_Model_Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getAvailablePaymentMethods()
    {
        return Mage::getStoreConfig('payment/pagarme_settings/payment_methods');
    }

    public function hasFixedDiscountOnBoleto()
    {
        return Mage::getStoreConfig('payment/pagarme_settings/boleto_discount_mode')
            == PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode::FIXED_VALUE;
    }

    private function hasPercentageDiscountOnBoleto()
    {
        return Mage::getStoreConfig('payment/pagarme_settings/boleto_discount_mode')
            == PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode::PERCENTAGE;
    }

    private function getBoletoDiscount()
    {
        return Mage::getStoreConfig(
            'payment/pagarme_settings/boleto_discount'
        );
    }

    /**
     * @return array
     */
    public function getCheckoutConfig()
    {
        $quote = $this->getQuote();
        $billingAddress = $quote->getBillingAddress();

        if ($billingAddress == false) {
            return false;
        }

        $telephone = $billingAddress->getTelephone();

        $helper = Mage::helper('pagarme_core');

        $cardBrands = \Mage::getStoreConfig(
            'payment/pagarme_settings/allowed_credit_card_brands'
        );

        $config = [
            'amount' => $helper->parseAmountToInteger($quote->getGrandTotal()),
            'createToken' => 'true',
            'paymentMethods' => $this->getAvailablePaymentMethods(),
            'customerName' => $helper->getCustomerNameFromQuote($quote),
            'customerEmail' => $quote->getCustomerEmail(),
            'customerDocumentNumber' => $quote->getCustomerTaxvat(),
            'customerPhoneDdd' => $helper->getDddFromPhoneNumber($telephone),
            'customerPhoneNumber' => $helper->getPhoneWithoutDdd($telephone),
            'customerAddressZipcode' => $billingAddress->getPostcode(),
            'customerAddressStreet' => $billingAddress->getStreet(1),
            'customerAddressStreetNumber' => $billingAddress->getStreet(2),
            'customerAddressComplementary' => $billingAddress->getStreet(3),
            'customerAddressNeighborhood' => $billingAddress->getStreet(4),
            'customerAddressCity' => $billingAddress->getCity(),
            'customerAddressState' => $billingAddress->getRegion(),
            'brands' => $cardBrands,
            'boletoHelperText' => Mage::getStoreConfig(
                'payment/pagarme_settings/boleto_helper_text'
            ),
            'creditCardHelperText' => Mage::getStoreConfig(
                'payment/pagarme_settings/credit_card_helper_text'
            ),
            'uiColor' => Mage::getStoreConfig(
                'payment/pagarme_settings/ui_color'
            ),
            'headerText' => Mage::getStoreConfig(
                'payment/pagarme_settings/header_text'
            ),
            'paymentButtonText' => Mage::getStoreConfig(
                'payment/pagarme_settings/payment_button_text'
            ),
            'interestRate' => Mage::getStoreConfig(
                'payment/pagarme_settings/interest_rate'
            ),
            'maxInstallments' => Mage::getStoreConfig(
                'payment/pagarme_settings/max_installments'
            ),
            'freeInstallments' => Mage::getStoreConfig(
                'payment/pagarme_settings/free_installments'
            ),
            'customerData' => Mage::getStoreConfig(
                'payment/pagarme_settings/capture_customer_data'
            )
        ];

        if ($this->hasFixedDiscountOnBoleto()) {
            $config['boletoDiscountAmount'] = $helper->parseAmountToInteger(
                $this->getBoletoDiscount()
            );
        }

        if ($this->hasPercentageDiscountOnBoleto()) {
            $config['boletoDiscountPercentage'] = $this->getBoletoDiscount();
        }

        return $config;
    }
}
