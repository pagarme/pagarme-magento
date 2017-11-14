<?php

class PagarMe_V2_CreditCard_Block_Info_Creditcard extends Mage_Payment_Block_Info_Cc
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('');
    }
}

