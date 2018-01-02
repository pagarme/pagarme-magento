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
            'amount'                       => 1234,
            'createToken'                  => 'true',
            'paymentMethods'               => Mage::getStoreConfig('payment/pagarme_configurations/modal_payment_methods'),
            'customerName'                 => 'Amazing Spider Man',
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
            'brands' => $this->brands,
            'customerData' => Mage::getStoreConfig('payment/pagarme_configurations/modal_capture_customer_data'),
            'boletoHelperText' => Mage::getStoreConfig(
                'payment/pagarme_configurations/modal_boleto_helper_text'
            ),
            'creditCardHelperText' => Mage::getStoreConfig(
                'payment/pagarme_configurations/modal_credit_card_helper_text'
            ),
            'uiColor' => Mage::getStoreConfig(
                'payment/pagarme_configurations/modal_ui_color'
            ),
            'headerText' => Mage::getStoreConfig(
                'payment/pagarme_configurations/modal_header_text'
            ),
            'paymentButtonText' => Mage::getStoreConfig(
                'payment/pagarme_configurations/modal_payment_button_text'
            ),
            'interestRate' => Mage::getStoreConfig('payment/pagarme_configurations/creditcard_interest_rate'
            ),
            'maxInstallments' => Mage::getStoreConfig('payment/pagarme_configurations/creditcard_max_installments'
            ),
            'freeInstallments' => Mage::getStoreConfig('payment/pagarme_configurations/creditcard_free_installments'
            ),
            'postbackUrl' => '',
        ];

        $customer = Mage::getModel('customer/customer')
            ->setEmail($checkoutConfig['customerEmail'])
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
            'payment/pagarme_configurations/creditcard_allowed_credit_card_brands',
            $this->brands
        );

        $customer = Mage::getModel('customer/customer')
            ->load($customer->getId());
        $address = Mage::getModel('customer/address')
            ->load($address->getId());

        $quote = Mage::getModel('sales/quote')
            ->setGrandTotal('12.34')
            ->setCustomerFirstname('Amazing')
            ->setCustomerMiddlename('Spider')
            ->setCustomerLastname('Man')
            ->setCustomerEmail($checkoutConfig['customerEmail'])
            ->setCustomerTaxvat($checkoutConfig['customerDocumentNumber']);
        $quote->setBillingAddress($address);

        $checkoutBlock = new PagarMe_Modal_Block_Form_Modal();
        $checkoutBlock->setQuote($quote);
        $this->assertEquals(
            $checkoutConfig,
            $checkoutBlock->getCheckoutConfig()
        );
    }
}
