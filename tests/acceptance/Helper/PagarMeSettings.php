<?php

namespace PagarMe\Magento\Test\Helper;

trait PagarMeSettings
{
    public function restorePagarMeSettings()
    {
        foreach ($this->getDefaultSettings() as $key => $defaultSetting) {
            \Mage::getModel('core/config')
                ->saveConfig(
                    "payment/pagarme_settings/{$key}",
                    $defaultSetting
                );
        }
    }

    public function getDefaultSettings()
    {
        return [
            'active' => '1',
            'payment_method' => 'credit_card,boleto',
            'capture_customer_data' => 'true',
            'payment_button_text' => '',
            'interest_rate' => '0',
            'free_installments' => '1',
            'max_installments' => '1',
            'allowed_credit_card_brands' => 'visa,mastercard,amex,hipercard,aura,jcb,diners,elo',
            'boleto_helper_text' => '',
            'credit_card_helper_text' => '',
            'ui_color' => '',
            'header_text' => '',
            'checkout_button_text' => '',
            'payment_action' => 'authorize_capture',
            'title' => 'Pagar.me Checkout'
        ];
    }
}
