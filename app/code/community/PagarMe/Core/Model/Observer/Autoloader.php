<?php

class PagarMe_Core_Model_Observer_Autoloader extends Varien_Event_Observer
{
    /**
     * @codeCoverageIgnore
     *
     * @param Varien_Event_Observer $event
     */
    public function registerSplAutoloader($event)
    {
        require_once Mage::getBaseDir() . '/vendor/autoload.php';
    }
}
