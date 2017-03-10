<?php

class PagarMe_Core_Model_Sdk_Adapter extends Mage_Core_Model_Abstract
{
    /**
     * @var \PagarMe\Sdk\PagarMe
     */
    private $pagarMeSdk;

    public function _construct()
    {
        parent::_construct();

        $this->pagarMeSdk = new \PagarMe\Sdk\PagarMe(
            Mage::getStoreConfig('payment/pagarme_settings/api_key')
        );
    }

    /**
     * @return \PagarMe\Sdk\PagarMe
     */
    public function getPagarMeSdk()
    {
        return $this->pagarMeSdk;
    }
}
