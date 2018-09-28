<?php

namespace PagarMe\Magento\Test\Helper\Configuration;

trait Inovarti
{
    private function enableInovartiOneStepCheckout()
    {
        $this->switchInovartiOneStepCheckout(1);
    }

    private function disableInovartiOneStepCheckout()
    {
        $this->switchInovartiOneStepCheckout(0);
    }

    private function switchInovartiOneStepCheckout($option)
    {
        \Mage::getModel('core/config')->saveConfig('onestepcheckout/general/is_enabled', $option);
    }
}
