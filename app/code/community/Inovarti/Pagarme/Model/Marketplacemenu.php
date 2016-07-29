<?php

class Inovarti_Pagarme_Model_Marketplacemenu extends Mage_Core_Model_Abstract
{
    /**
     * Inovarti_Pagarme_Model_Splitrules constructor.
     */
    protected function _construct()
    {
        return $this->_init('pagarme/marketplacemenu');
    }
}