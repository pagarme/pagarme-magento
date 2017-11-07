<?php

namespace PagarMe\Magento\Test\Helper;

trait PagarMeSettings
{
    public function restorePagarMeSettings()
    {
        foreach ($this->getDefaultSettings() as $key => $defaultSetting) {
            \Mage::getModel('core/config')
                ->saveConfig(
                    "payment/pagarme_v2_settings/{$key}",
                    $defaultSetting
                );
        }
    }

    public function getDefaultSettings()
    {
        return [
            'checkout_active' => '1',
            'checkout_payment_methods' => 'credit_card,boleto',
            'checkout_capture_customer_data' => 'true',
            'checkout_payment_button_text' => '',
            'creditcard_interest_rate' => '0',
            'creditcard_free_installments' => '1',
            'creditcard_max_installments' => '1',
            'creditcard_allowed_credit_card_brands' => 'visa,mastercard,amex,hipercard,aura,jcb,diners,elo',
            'checkout_boleto_helper_text' => '',
            'checkout_credit_card_helper_text' => '',
            'checkout_ui_color' => '',
            'checkout_header_text' => '',
            'checkout_button_text' => '',
            'payment_action' => 'authorize_capture',
            'checkout_title' => 'Pagar.me Checkout',
            'creditcard_title' => 'Cartão de crédito Pagar.me',
            'transparent_payment_methods' => 'credit_card',
            'transparent_active' => '1'
        ];
    }
}
