<?php

class Inovarti_Pagarme_Model_Resource_Splitrules extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('pagarme/splitrules', 'entity_id');
    }
}