<?php

class PagarMe_Boleto_Block_Info_Boleto extends Mage_Payment_Block_Info
{
    use PagarMe_Core_Block_Info_Trait;

    private $helper;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate(
            'pagarme/boleto/order_info/payment_details.phtml'
        );
        $this->helper = Mage::helper('pagarme_boleto');
    }

    public function transactionId()
    {
        return $this->getTransaction()->getId();
    }

    public function getBoletoUrl()
    {
        return $this->getTransaction()->getBoletoUrl();
    }
}