<?php

use PagarMe_Core_Model_CurrentOrder as CurrentOrder;

class PagarMe_Creditcard_Block_Form_CreditCard extends Mage_Payment_Block_Form_Cc
{
    use PagarMe_Core_Trait_ConfigurationsAccessor;

    const TEMPLATE = 'pagarme/form/credit_card.phtml';

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate(self::TEMPLATE);
    }

    public function getInstallments()
    {
        $quote = Mage::helper('checkout')->getQuote();
        $pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter');
        $currentOrder = new CurrentOrder(
            $quote,
            $pagarMeSdk
        );

        return $currentOrder->calculateInstallments(
            $this->getMaxInstallmentStoreConfig(),
            $this->getFreeInstallmentStoreConfig(),
            $this->getInterestRateStoreConfig()
        );
    }
}

