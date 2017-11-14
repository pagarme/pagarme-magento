<?php

class PagarMe_V2_Core_Model_Transaction extends Mage_Core_Model_Abstract
{
    /**
     * @return type
     */
    public function _construct()
    {
        return $this->_init('pagarme_v2_core/transaction');
    }
}
