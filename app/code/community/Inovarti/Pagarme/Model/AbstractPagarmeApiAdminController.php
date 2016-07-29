<?php

class Inovarti_Pagarme_Model_AbstractPagarmeApiAdminController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return $this
     */
    public function _construct()
    {
        $apiMode = Mage::getStoreConfig('payment/pagarme_settings/mode');
        $apiKey  = Mage::getStoreConfig('payment/pagarme_settings/apikey_' . $apiMode);

        Pagarme::setApiKey($apiKey);
        return $this;
    }
}