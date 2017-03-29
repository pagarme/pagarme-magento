<?php

class PagarMe_Checkout_Block_Form_CheckoutTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function mustReturnCheckoutConfig()
    {
        $checkoutConfig = [
            'amount'                       => 1234,
            'createToken'                  => 'true',
            'paymentMethods'               => Mage::getStoreConfig('payment/pagarme_settings/payment_methods'),
            'customerName'                 => 'John Due',
            'customerEmail'                => mktime() . 'john.due@email.com',
            'customerDocumentNumber'       => '123.456.789-52',
            'customerPhoneDdd'             => '15',
            'customerPhoneNumber'          => '958483521',
            'customerAddressZipcode'       => '12345678',
            'customerAddressStreet'        => 'Potato Av',
            'customerAddressStreetNumber'  => '123',
            'customerAddressComplementary' => '',
            'customerAddressNeighborhood' => 'Downtown',
            'customerAddressCity' => 'Nowhere',
            'customerAddressState' => 'XP',
            'interestRate' => Mage::getStoreConfig('payment/pagarme_settings/interest_rate'),
            'maxInstallments' => Mage::getStoreConfig('payment/pagarme_settings/max_installments'),
            'freeInstallments' => Mage::getStoreConfig('payment/pagarme_settings/free_installments')
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

        $customer = Mage::getModel('customer/customer')
            ->load($customer->getId());

        $checkoutBlock = new PagarMe_Checkout_Block_Form_Checkout();
        $checkoutBlock->setCustomer($customer);
        $checkoutBlock->setQuote($quote);

        $this->assertEquals(
            $checkoutConfig,
            $checkoutBlock->getCheckoutConfig()
        );
    }
}
