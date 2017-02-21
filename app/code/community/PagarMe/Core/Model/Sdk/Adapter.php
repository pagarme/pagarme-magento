<?php

class PagarMe_Core_Model_Sdk_Adapter extends Mage_Core_Model_Abstract 
{
    private $pagarMeSdk;

    public function _construct()
    {
        parent::_construct();
        $this->pagarMeSdk = new \PagarMe\Sdk\PagarMe('eduardo');
    }

    public function getPagarMeSdk()
    {
        return $this->pagarMeSdk;
    }
}