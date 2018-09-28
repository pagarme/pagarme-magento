<?php

namespace PagarMe\Magento\Test\Helper;

trait CustomerDataProvider
{
    public function getCustomer()
    {
        \Mage::app()
            ->setCurrentStore(1);

        $websiteId = \Mage::app()
            ->getWebsite()
            ->getId();

        $store = \Mage::app()
            ->getStore();

        \Mage::app()
            ->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);

        $customer = \Mage::getModel('customer/customer')
            ->setWebsiteId($websiteId)
            ->setStore($store)
            ->setFirstname('Lívia Nina')
            ->setLastname('Isabelle Freitas')
            ->setTaxvat('332.840.319-10')
            ->setDob('03/12/1980')
            ->setEmail((mktime()+ rand(1, 99)) . 'livia_nina@arganet.com.br')
            ->setPassword('123456');

        return $customer;
    }

    public function getCustomerAddress()
    {
        $address = \Mage::getModel('customer/address')
            ->setData(
                array(
                    'firstname'  => 'Lívia Nina',
                    'lastname'   => 'Isabelle Freitas',
                    'street'     => array(
                        '0' => 'Rua Siqueira Campos',
                        '1' => '515',
                        '2' => '',
                        '3' => 'Jacintinho'
                    ),
                    'city'       => 'Maceió',
                    'region_id'  => '',
                    'region'     => 'SP',
                    'postcode'   => '57040460',
                    'country_id' => 'BR',
                    'telephone'  => '(82) 99672-3631'
                )
            )
            ->setIsDefaultBilling('1')
            ->setIsDefaultShipping('1')
            ->setSaveInAddressBook('1');

        return $address;
    }
}
