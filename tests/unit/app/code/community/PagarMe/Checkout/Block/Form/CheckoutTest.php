<?php

class PagarMe_Checkout_Block_Form_CheckoutTest extends PHPUnit_Framework_TestCase
{
    private $brands = 'mastercard,visa,elo,aura';

    /**
     * @test
     */
    public function mustReturnCheckoutConfig()
    {
        $checkoutConfig = [
            'amount' => 1234,
            'createToken' => 'true',
            'paymentMethods' => Mage::getStoreConfig('payment/pagarme_settings/payment_methods'),
            'customerName' => 'John Due',
            'customerEmail' => mktime() . 'john.due@email.com',
            'customerDocumentNumber' => '123.456.789-52',
            'customerPhoneDdd' => '15',
            'customerPhoneNumber' => '958483521',
            'customerAddressZipcode' => '12345678',
            'customerAddressStreet' => 'Potato Av',
            'customerAddressStreetNumber' => '123',
            'customerAddressComplementary' => '',
            'customerAddressNeighborhood' => 'Downtown',
            'customerAddressCity' => 'Nowhere',
            'customerAddressState' => 'XP',
            'brands' => $this->brands
        ];

        $quote = Mage::getModel('sales/quote')
            ->setGrandTotal('12.34');

        $customer = Mage::getModel('customer/customer')
            ->setFirstname('John')
            ->setLastname('Due')
            ->setEmail($checkoutConfig['customerEmail'])
            ->setTaxvat($checkoutConfig['customerDocumentNumber'])
            ->save();

        $address = Mage::getModel('customer/address')
            ->setCustomerId($customer->getId())
            ->setPostcode($checkoutConfig['customerAddressZipcode'])
            ->setStreet([
                $checkoutConfig['customerAddressStreet'],
                $checkoutConfig['customerAddressStreetNumber'],
                $checkoutConfig['customerAddressComplementary'],
                $checkoutConfig['customerAddressNeighborhood']
            ])
            ->setCity($checkoutConfig['customerAddressCity'])
            ->setRegion($checkoutConfig['customerAddressState'])
            ->setTelephone($checkoutConfig['customerPhoneDdd'].$checkoutConfig['customerPhoneNumber'])
            ->setIsDefaultBilling(true)
            ->setIsDefaultShipping(true)
            ->setSaveInAddressBook(true)
            ->save();

        \Mage::app()->getStore()->setConfig(
            'payment/pagarme_settings/allowed_credit_card_brands',
            $this->brands
        );

        $customer = Mage::getModel('customer/customer')
            ->load($customer->getId());

        $checkoutBlock = new PagarMe_Checkout_Block_Form_Checkout();
        $checkoutBlock->setCustomer($customer);
        $checkoutBlock->setQuote($quote);

        $this->assertEquals(
            json_encode($checkoutConfig),
            $checkoutBlock->getCheckoutConfig()
        );
    }
}
