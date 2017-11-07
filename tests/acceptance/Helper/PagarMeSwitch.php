<?php

namespace PagarMe\Magento\Test\Helper;

trait PagarMeSwitch
{
    protected function enablePagarmeCheckout()
    {
        $this->changePagarmeSetting(
            'payment/pagarme_v2_settings/checkout_active',
            true
        );
    }

    protected function disablePagarmeCheckout()
    {
        $this->changePagarmeSetting(
            'payment/pagarme_v2_settings/checkout_active',
            false
        );
    }

    protected function enablePagarmeTransparent()
    {
        $this->changePagarmeSetting(
            'payment/pagarme_v2_settings/transparent_active',
            true
        );
    }

    protected function disablePagarmeTransparent()
    {
        $this->changePagarmeSetting(
            'payment/pagarme_v2_settings/transparent_active',
            false
        );
    }

    protected function changePagarmeSetting($name, $value)
    {
        \Mage::getConfig()->saveConfig($name, $value);
        \Mage::getConfig()->cleanCache();
    }
}

