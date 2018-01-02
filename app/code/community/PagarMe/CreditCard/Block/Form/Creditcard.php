<?php

class PagarMe_Creditcard_Block_Form_CreditCard extends Mage_Payment_Block_Form_Cc
{
    const TEMPLATE = 'pagarme/form/credit_card.phtml';

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate(self::TEMPLATE);
    }

    public function getEncryptionKey()
    {
        return Mage::getStoreConfig(
            'payment/pagarme_configurations/general_encryption_key'
        );
    }

    public function getCheckoutConfig()
    {
        $quote = $this->getQuote();
    }
}

