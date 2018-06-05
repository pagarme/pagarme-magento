<?php

class PagarMe_Core_Model_Sdk_Adapter extends Mage_Core_Model_Abstract
{
    /**
     * @var \PagarMe\Sdk\PagarMe
     */
    private $pagarMeSdk;

    /**
     * Timeout in seconds
     * @var int
     */
    const SDK_TIMEOUT = 20;

    public function _construct()
    {
        parent::_construct();

        $this->pagarMeSdk = new \PagarMe\Sdk\PagarMe(
            Mage::getStoreConfig('payment/pagarme_configurations/general_api_key'),
            self::SDK_TIMEOUT,
            $this->getUserAgent()
        );
    }

    /**
     * @return \PagarMe\Sdk\PagarMe
     */
    public function getPagarMeSdk()
    {
        return $this->pagarMeSdk;
    }

    /**
     * @return array
     */
    public function getUserAgent()
    {
        return [
            'User-Agent' => sprintf(
                'Magento/%s PagarMe/%s PHP/%s',
                Mage::getVersion(),
                Mage::getConfig()->getNode()->modules->PagarMe_Core->version,
                phpversion()
            )
        ];
    }
}
