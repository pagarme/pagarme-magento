<?php

namespace PagarMe\Magento\Test\Helper;

use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\Transaction\BoletoTransaction;

trait PostbackDataProvider
{
    public function getOrderPaidByBoleto(
        $customer,
        $customerAddress,
        $products
    ) {
        return $this->getOrderPaid(
            BoletoTransaction::PAYMENT_METHOD,
            $customer,
            $customerAddress,
            $products
        );
    }

    public function getOrderPaidByCreditCard(
        $customer,
        $customerAddress,
        $products
    ) {
        return $this->getOrderPaid(
            CreditCardTransaction::PAYMENT_METHOD,
            $customer,
            $customerAddress,
            $products
        );
    }

    private function getOrderPaid(
        $paymentMethod,
        $customer,
        $customerAddress,
        $products
    ) {
        $quote = $this->createQuote($customer, $customerAddress, $products);

        if ($paymentMethod == BoletoTransaction::PAYMENT_METHOD) {
            $token = $this->createTokenBoletoTransaction(
                $quote->getGrandTotal(),
                $customer,
                $customerAddress
            );
        } elseif ($paymentMethod == CreditCardTransaction::PAYMENT_METHOD) {
            $token = $this->createTokenCreditCardTransaction(
                $quote->getGrandTotal(),
                $customer,
                $customerAddress
            );
        }

        $dataQuote = [
            'method' => 'pagarme_checkout',
            'pagarme_checkout_payment_method' => $paymentMethod,
            'pagarme_checkout_token' => $token
        ];

        $quote->getPayment()->importData($dataQuote);
        $quote->collectTotals()->save();

        $service = \Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();

        $order = $service->getOrder();

        return $order;
    }

    private function createQuote(
        $customer,
        $customerAddress,
        $products
    ) {
        \Mage::app()
            ->setCurrentStore(1);

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

        $quote->collectTotals();

        return $quote;
    }

    private function createTokenBoletoTransaction(
        $amount,
        $customer,
        $customerAddress
    ) {
        $bodyData = $this->createCommonDataForToken(
            $amount,
            $customer,
            $customerAddress
        );

        $bodyData['payment_method'] = BoletoTransaction::PAYMENT_METHOD;

        return $this->createToken($bodyData);
    }

    private function createTokenCreditCardTransaction(
        $amount,
        $customer,
        $customerAddress
    ) {
        $bodyData = $this->createCommonDataForToken(
            $amount,
            $customer,
            $customerAddress
        );

        $bodyData['payment_method'] = CreditCardTransaction::PAYMENT_METHOD;

        $bodyData = array_merge(
            $this->getCreditCardData(),
            $bodyData
        );

        return $this->createToken($bodyData);
    }

    private function createToken($bodyData)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            'https://api.pagar.me/1/transactions',
            [
                'body' => $bodyData
            ]
        );

        $responseObj = json_decode((string) $response->getBody());
        return $responseObj->token;
    }

    private function getCreditCardData()
    {
        return [
            'card_number' => '4111111111111111',
            'card_holder_name' => 'Ricardo Ledo',
            'card_expiration_date' => '0220',
            'card_cvv' => '231'
        ];
    }

    private function createCommonDataForToken($amount, $customer, $customerAddress)
    {
        $helper = \Mage::helper('pagarme_core');

        $encryptionKey = \Mage::getStoreConfig(
            'payment/pagarme_settings/encryption_key'
        );

        return [
            'amount' => '' . (int) ($amount * 100),
            'postback_url' => 'http://requestb.in/pkt7pgpk',
            'customer' => [
                'name' => $customer->getName(),
                'email' => $customer->getEmail(),
                'document_number' => $customer->getTaxvat(),
                'address' => [
                    'zipcode' => $customerAddress->getPostcode(),
                    'neighborhood' => $customerAddress->getStreet(4),
                    'street' => $customerAddress->getStreet(1),
                    'street_number' => $customerAddress->getStreet(2)
                ],
                'phone' => [
                    'ddi' => '55',
                    'ddd' => $helper->getDddFromPhoneNumber($customerAddress->getTelephone()),
                    'number' => $helper->getPhoneWithoutDdd($customerAddress->getTelephone())
                ]
            ],
            'metadata' => ['idProduto' => '13933139'],
            'capture' => 'false',
            'encryption_key' => $encryptionKey
        ];
    }
}
