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

    /**
     * @return string
     */
    public function getCheckoutConfig()
    {
        $quote = $this->getQuote();
        $customer = $this->getCustomer();
        $address = $customer->getDefaultBillingAddress();

        $helper = Mage::helper('pagarme_core');

        $telephone = $address->getTelephone();

        $cardBrands = \Mage::getStoreConfig(
            'payment/pagarme_settings/allowed_credit_card_brands'
        );

        return json_encode([
            'amount' => $helper->parseAmountToInteger($quote->getGrandTotal()),
            'createToken' => 'true',
            'paymentMethods' => $this->getAvailablePaymentMethods(),
            'customerName' => $customer->getName(),
            'customerEmail' => $customer->getEmail(),
            'customerDocumentNumber' => $customer->getTaxvat(),
            'customerPhoneDdd' => $helper->getDddFromPhoneNumber($telephone),
            'customerPhoneNumber' => $helper->getPhoneWithoutDdd($telephone),
            'customerAddressZipcode' => $address->getPostcode(),
            'customerAddressStreet' => $address->getStreet(1),
            'customerAddressStreetNumber' => $address->getStreet(2),
            'customerAddressComplementary' => $address->getStreet(3),
            'customerAddressNeighborhood' => $address->getStreet(4),
            'customerAddressCity' => $address->getCity(),
            'customerAddressState' => $address->getRegion(),
            'brands' => $cardBrands
        ]);
    }
}
