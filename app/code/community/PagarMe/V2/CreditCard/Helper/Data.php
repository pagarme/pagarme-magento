<?php

class PagarMe_V2_CreditCard_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function buildCustomer()
    {
        $quote = $this->getQuote();
        $billingAddress = $quote->getBillingAddress();

        if ($billingAddress == false) {
            return false;
        }

        $telephone = $billingAddress->getTelephone();

        $helper = Mage::helper('pagarme_v2_core');

        $config = [
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

            'customerData' => Mage::getStoreConfig(
                'payment/pagarme_v2_settings/checkout_capture_customer_data'
            )
        ];

        return $config;
    }
}
