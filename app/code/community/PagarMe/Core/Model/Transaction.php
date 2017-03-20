<?php

class PagarMe_Core_Model_Transaction extends Mage_Core_Model_Abstract
{
    /**
     * @return PagarMe_Core_Model_Transaction
     *
     * @codeCoverageIgnore
     */
    public function _construct()
    {
        return $this->_init('pagarme_core/transaction');
    }
}
