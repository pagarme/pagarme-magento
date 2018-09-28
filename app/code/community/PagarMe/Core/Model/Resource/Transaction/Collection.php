<?php

class PagarMe_Core_Model_Resource_Transaction_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * @codeCoverageIgnore
     */
    public function _construct()
    {
        parent::_construct();

        $this->_init('pagarme_core/transaction');
    }
}
