<?php

trait PagarMe_Core_Trait_ConfigurationsAccessor
{
    /**
     * Returns true only if magento is running with developer mode enabled
     *
     * @return bool
     */
    public function isDeveloperModeEnabled()
    {
        if (Mage::getIsDeveloperMode() ||
            getenv('PAGARME_DEVELOPMENT') === 'enabled'
        ) {
            return true;
        }

        return false;
    }

    /**
     * Returns postback url defined on Pagar.me's settings panel
     *
     * @return string
     */
    private function getDevelopmentPostbackUrl()
    {
        $devPostbackUrl = trim($this->getConfigurationWithName(
            'pagarme_configurations/dev_custom_postback_url'
        ));

        if (!filter_var($devPostbackUrl, FILTER_VALIDATE_URL)) {
            return '';
        }

        if (substr($devPostbackUrl, 1, 1) !== '/') {
            $devPostbackUrl .= '/';
        }

        return $devPostbackUrl;
    }

    /**
     * @return bool
     */
    private function isTransparentCheckoutActiveStoreConfig()
    {
        return (bool) $this->getConfigurationWithName(
            'pagarme_configurations/transparent_active'
        );
    }

    /**
     * @return string
     */
    private function getCreditcardTitleStoreConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_title'
        );
    }

    /**
     * @return int
     */
    private function getMaxInstallmentStoreConfig()
    {
        return (int) $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_max_installments'
        );
    }

    /**
     * @return float
     */
    private function getMinInstallmentValueStoreConfig()
    {
        return (float) $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_min_installment_value'
        );
    }

    /**
     * @return string
     */
    public function getEncryptionKeyStoreConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/general_encryption_key'
        );
    }

    /**
     * @return bool
     */
    public function getAsyncTransactionConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/async_transaction'
        );
    }

    /**
     * @return string
     */
    public function getPaymentActionConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/payment_action'
        );
    }

    /**
     * @return int
     */
    private function getFreeInstallmentStoreConfig()
    {
        return (int) $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_free_installments'
        );
    }

    /**
     * @return float
     */
    private function getInterestRateStoreConfig()
    {
        return (float) $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_interest_rate'
        );
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    private function getConfigurationWithName($name)
    {
        return Mage::getStoreConfig("payment/{$name}");
    }
}
