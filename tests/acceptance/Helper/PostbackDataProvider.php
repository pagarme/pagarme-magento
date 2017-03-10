<?php

namespace PagarMe\Magento\Test\Helper;

trait PostbackDataProvider
{
    public function getOrder($customer, $customerAddress, $products)
    {
        \Mage::app()
            ->setCurrentStore(1);

        $helper = \Mage::helper('pagarme_core');

        $quote = \Mage::getModel('sales/quote')
            ->setStoreId(
                \Mage::app()->getStore()->getStoreId()
            )
            ->assignCustomer($customer);

        $quote->getBillingAddress()
            ->importCustomerAddress($customerAddress);

        $quote->getShippingAddress()
            ->importCustomerAddress($customerAddress);

        $quote->getShippingAddress()
            ->setCollectShippingRates(true);

        foreach ($products as $product) {
            $quote->addProduct($product, 1);
        }

        $quote->getShippingAddress()
            ->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('flatrate_flatrate');

        $quote->getPayment()->importData(
            [
                'method' => 'pagarme_checkout',
                'pagarme_checkout_payment_method' => 'boleto',
                'pagarme_checkout_customer_document_number' => $customer->getTaxvat(),
                'pagarme_checkout_customer_document_type' => 'cpf',
                'pagarme_checkout_customer_name' => $customer->getName(),
                'pagarme_checkout_customer_email' => $customer->getEmail(),
                'pagarme_checkout_customer_born_at' => $customer->getDob(),
                'pagarme_checkout_customer_phone_ddd' => $helper->getDddFromPhoneNumber($customerAddress->getTelephone()),
                'pagarme_checkout_customer_phone_number' => $helper->getPhoneWithoutDdd($customerAddress->getTelephone()),
                'pagarme_checkout_customer_address_street_1' => $customerAddress->getStreet(1),
                'pagarme_checkout_customer_address_street_2' => $customerAddress->getStreet(2),
                'pagarme_checkout_customer_address_street_3' => $customerAddress->getStreet(3),
                'pagarme_checkout_customer_address_street_4' => $customerAddress->getStreet(4),
                'pagarme_checkout_customer_address_city' => $customerAddress->getCity(),
                'pagarme_checkout_customer_address_state' => $customerAddress->getRegion(),
                'pagarme_checkout_customer_address_zipcode' => $customerAddress->getPostcode(),
                'pagarme_checkout_customer_address_country' => $customerAddress->getCountryId(),
                'pagarme_checkout_customer_gender' => $customer->getGender()
            ]
        );

        $quote->collectTotals()->save();

        $service = \Mage::getModel('sales/service_quote', $quote);

        $service->submitAll();

        $order = $service->getOrder();

        return $order;
    }
}
