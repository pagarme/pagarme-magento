<?php

class CoreModuleTest extends PHPUnit_Framework_TestCase
{
    const MODULE_NAME = 'PagarMe_Core';

    public function setUp()
    {
        Mage::init();
        Mage::app()
            ->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
    }

    /**
     * @test
     */
    public function mustHaveBeenInstalled()
    {
        $modules = (array) Mage::getConfig()
            ->getNode('modules')
            ->children();

        $this->assertTrue(isset($modules[self::MODULE_NAME]));
    }

    /**
     * @test
     */
    public function mustBeActive()
    {
        $this->assertTrue(Mage::helper('core')->isModuleEnabled(self::MODULE_NAME));
    }
}
