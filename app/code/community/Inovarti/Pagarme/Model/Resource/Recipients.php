<?php

class Inovarti_Pagarme_Model_Resource_Recipients extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('pagarme/recipients', 'entity_id');
    }
}