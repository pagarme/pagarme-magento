<?php

namespace PagarMe\Magento\Test\Helper;

class PaymentMethodSettingsProvider
{
    const CREDIT_CARD = 'credit_card';
    const BOLETO = 'boleto';
    const CREDIT_CARD_AND_BOLETO = 'credit_card,boleto';

    public static function setPaymentMethodsAvailable($paymentMethods)
    {
        $config = \Mage::getModel('core/config')
            ->saveConfig(
                'payment/pagarme_configurations/modal_payment_methods',
                $paymentMethods
            );

        \Mage::app()->getStore()->resetConfig();
    }

    public static function getAvailablePaymentMethods()
    {
        return \Mage::getModel('core/config')
            ->getConfig(
                'payment/pagarme_configurations/modal_payment_methods'
            );
    }
}
