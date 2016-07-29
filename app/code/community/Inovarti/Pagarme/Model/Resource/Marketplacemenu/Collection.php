<?php

class Inovarti_Pagarme_Model_Resource_Marketplacemenu_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('pagarme/marketplacemenu');
    }
}