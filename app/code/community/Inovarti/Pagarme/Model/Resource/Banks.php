<?php

class Inovarti_Pagarme_Model_Resource_Banks
    extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('pagarme/banks', 'entity_id');
    }
}