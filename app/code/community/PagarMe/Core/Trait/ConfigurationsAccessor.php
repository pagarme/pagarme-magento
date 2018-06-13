<?php

trait PagarMe_Core_Trait_ConfigurationsAccessor
{

    private function isTransparentCheckoutActiveStoreConfig()
    {
        return (bool) $this->getConfigurationWithName(
            'pagarme_configurations/transparent_active'
        );
    }

    private function getCreditcardTitleStoreConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_title'
        );
    }

    private function getMaxInstallmentStoreConfig()
    {
        return (int) $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_max_installments'
        );
    }

    public function getEncryptionKeyStoreConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/general_encryption_key'
        );
    }

    public function getAsyncTransactionConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/async_transaction'
        );
    }

    public function getPaymentActionConfig()
    {
        return $this->getConfigurationWithName(
            'pagarme_configurations/payment_action'
        );
    }

    private function getFreeInstallmentStoreConfig()
    {
        return (int) $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_free_installments'
        );
    }

    private function getInterestRateStoreConfig()
    {
        return (float) $this->getConfigurationWithName(
            'pagarme_configurations/creditcard_interest_rate'
        );
    }

    private function getConfigurationWithName($name)
    {
        return Mage::getStoreConfig("payment/{$name}");
    }

}
