<?php

class PagarMe_CreditCard_Model_Creditcard extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'pagarme_creditcard';
    protected $_formBlockType = 'pagarme_creditcard/form_creditcard';
    protected $_infoBlockType = 'pagarme_creditcard/info_creditcard';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canRefund = true;
    protected $_canUseForMultishipping = true;
    protected $_canManageRecurringProfiles = true;

    /**
     * @param type $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if (!parent::isAvailable($quote)) {
            return false;
        }

        return (bool) Mage::getStoreConfig(
            'payment/pagarme_configurations/transparent_active'
        );
    }

   /**
     * Retrieve payment method title
     *
     * @return string
     */
    public function getTitle()
    {
        return Mage::getStoreConfig(
            'payment/pagarme_configurations/creditcard_title'
        );
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function assignData($data)
    {
        $additionalInfoData = [
            'card_hash' => $data['card_hash']
        ];

        $this->getInfoInstance()
            ->setAdditionalInformation($additionalInfoData);

        return $this;
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        $infoInstance = $this->getInfoInstance();
        $cardHash = $infoInstance->getAdditionalInformation('card_hash');
        try {
            $card = Mage::getModel('pagarme_core/sdk_adapter')
                ->getPagarMeSdk()
                ->card()
                ->createFromHash($cardHash);
        } catch (\Exception $exception) {
            $error = json_decode($exception->getMessage());
            $error = json_decode($error);

            $response = array_reduce($error->errors, function($carry, $item) {
                return is_null($carry) ? $item->message : $carry."\n".$item->message;
            });

            Mage::throwException($response);
        }

        $quote = Mage::getSingleton('checkout/session')->getQuote();

        $helper = Mage::helper('pagarme_core');

        $billingAddress = $quote->getBillingAddress();

        if ($billingAddress == false) {
            return false;
        }

        $telephone = $billingAddress->getTelephone();

        $customer = $helper->prepareCustomerData([
            'pagarme_modal_customer_document_number' => $quote->getCustomerTaxvat(),
            'pagarme_modal_customer_document_type' => $helper->getDocumentType($quote),
            'pagarme_modal_customer_name' => $helper->getCustomerNameFromQuote($quote),
            'pagarme_modal_customer_email' => $quote->getCustomerEmail(),
            'pagarme_modal_customer_born_at' => $quote->getDob(),
            'pagarme_modal_customer_address_street_1' => $billingAddress->getStreet(1),
            'pagarme_modal_customer_address_street_2' => $billingAddress->getStreet(2),
            'pagarme_modal_customer_address_street_3' => $billingAddress->getStreet(3),
            'pagarme_modal_customer_address_street_4' => $billingAddress->getStreet(4),
            'pagarme_modal_customer_address_city' => $billingAddress->getCity(),
            'pagarme_modal_customer_address_state' => $billingAddress->getRegion(),
            'pagarme_modal_customer_address_zipcode' => $billingAddress->getPostcode(),
            'pagarme_modal_customer_address_country' => $billingAddress->getCountry(),
            'pagarme_modal_customer_phone_ddd' => $helper->getDddFromPhoneNumber($telephone),
            'pagarme_modal_customer_phone_number' => $helper->getPhoneWithoutDdd($telephone),
            'pagarme_modal_customer_gender' => $quote->getGender()
        ]);

        $customerPagarMe = $helper->buildCustomer($customer);

        try {
            $pagarmeSdk = Mage::getModel('pagarme_core/sdk_adapter')
                ->getPagarMeSdk();

            $authorizedTransaction = $pagarmeSdk->transaction()
                ->creditCardTransaction(
                    $helper->parseAmountToInteger($quote->getGrandTotal()),
                    $card,
                    $customerPagarMe,
                    1,
                    false
                );

            $pagarmeSdk->transaction()->capture($authorizedTransaction);
        } catch (\Exception $exception) {
            $json = json_decode($exception->getMessage());
            $json = json_decode($json);

            $response = array_reduce($json->errors, function($carry, $item) {
                return is_null($carry)
                    ? $item->message : $carry."\n".$item->message;
            });

            Mage::throwException($response);
        }


        return $this;
    }

    public function capture(Varien_Object $payment, $amount)
    {
        $transaction = Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk()
            ->transaction()
            ->capture($authorizedTransaction);
    }
}
