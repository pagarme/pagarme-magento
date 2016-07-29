<?php

class Inovarti_Pagarme_Model_Resource_Marketplacemenu extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('pagarme/marketplacemenu', 'entity_id');
    }
}