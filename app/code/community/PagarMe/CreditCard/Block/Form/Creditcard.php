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

    public function getInstallments()
    {
        //Subtotal should be the sum of all items in the cart
        //there's also Basesubtotal = subtotal in the store's currency
        //Pode levar à demora de mostrar os métodos de pagamento
        $subtotal = $this->getCurrentSubtotal();
        $interestRate = Mage::getStoreConfig('payment/pagarme_configurations/creditcard_interest_rate');
        $maxInstallments = Mage::getStoreConfig('payment/pagarme_configurations/creditcard_max_installments');
        $freeInstallments = Mage::getStoreConfig('payment/pagarme_configurations/creditcard_free_installments');

        $pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter');
        $installments = $pagarMeSdk->getPagarMeSdk()->calculation()->calculateInstallmentsAmount(
            $subtotal,
            $interestRate,
            $freeInstallments,
            $maxInstallments
        );
        return $installments;
    }

    public function getCurrentSubtotal()
    {
        $subtotalPunctuated = Mage::getModel('checkout/session')->getQuote()->getData()['subtotal'];
        return preg_replace('/[^0-9]/', '', $subtotalPunctuated);
    }
}

