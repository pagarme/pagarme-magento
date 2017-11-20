<?php

class PagarMe_V2_Creditcard_Block_Form_CreditCard extends Mage_Payment_Block_Form_Cc
{
    const TEMPLATE = 'pagarmev2/form/pagarme_v2_credit_card.phtml';

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate(self::TEMPLATE);
    }

    public function getEncryptionKey()
    {
        return Mage::getStoreConfig('payment/pagarme_v2_settings/general_encryption_key');
    }

    public function getCheckoutConfig()
    {
        $quote = $this->getQuote();
    }
}

