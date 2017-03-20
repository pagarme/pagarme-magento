<?php

class PagarMe_Checkout_Model_CheckoutTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PagarMe_Checkout_Model_Checkout
     */
    protected $model;

    public function setUp()
    {
        $this->model = Mage::getModel('pagarme_checkout/checkout');
    }

    /**
     * @test
     */
    public function checkAvailability()
    {
        $model = Mage::getModel('pagarme_checkout/checkout');

        $this->setModelAvailability(false);
        $this->assertFalse($model->isAvailable());

        $this->setModelAvailability(true);
        $this->assertTrue($model->isAvailable());
    }
    
    /**
     * @param bool $value
     */
    private function setModelAvailability($value)
    {
        Mage::app()->getStore()->resetConfig();

        $nodePath = 'payment/pagarme_checkout/active';
        Mage::getConfig()->saveConfig($nodePath, $value);
        Mage::getConfig()->cleanCache();
    }
}
