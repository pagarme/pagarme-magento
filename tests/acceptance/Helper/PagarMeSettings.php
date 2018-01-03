<?php

namespace PagarMe\Magento\Test\Helper;

trait PagarMeSettings
{
    public function restorePagarMeSettings()
    {
        foreach ($this->getDefaultSettings() as $key => $defaultSetting) {
            \Mage::getModel('core/config')
                ->saveConfig(
                    "payment/pagarme_configurations/{$key}",
                    $defaultSetting
                );
        }
    }

    public function getDefaultSettings()
    {
        return [
            'modal_active' => '1',
            'modal_payment_methods' => 'credit_card,boleto',
            'modal_capture_customer_data' => 'true',
            'modal_payment_button_text' => '',
            'creditcard_interest_rate' => '0',
            'creditcard_free_installments' => '1',
            'creditcard_max_installments' => '12',
            'creditcard_allowed_credit_card_brands' => 'visa,mastercard,amex,hipercard,aura,jcb,diners,elo',
            'modal_boleto_helper_text' => '',
            'modal_credit_card_helper_text' => '',
            'modal_ui_color' => '',
            'modal_header_text' => '',
            'modal_button_text' => 'Confirm your information',
            'payment_action' => 'authorize_capture',
            'modal_title' => 'Pagar.me Checkout',
            'creditcard_title' => 'Cartão de crédito Pagar.me',
            'transparent_payment_methods' => 'credit_card',
            'transparent_active' => '1'
        ];
    }
}
