<?php

class PagarMe_Checkout_Block_Info_Checkout extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('pagarme/info/checkout.phtml');
    }
}
