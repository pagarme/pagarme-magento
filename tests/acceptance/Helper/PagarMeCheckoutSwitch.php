<?php

namespace PagarMe\Magento\Test\Helper;

trait PagarMeCheckoutSwitch
{
    protected function enablePagarmeCheckout()
    {
        $this->changePagarmeCheckout(true);
    }

    protected function disablePagarmeCheckout()
    {
        $this->changePagarmeCheckout(false);
    }

    protected function changePagarmeCheckout($value)
    {
        $nodePath = "payment/pagarme_checkout/active";
        \Mage::getConfig()->saveConfig($nodePath, $value);
        \Mage::getConfig()->cleanCache();
    }
}
