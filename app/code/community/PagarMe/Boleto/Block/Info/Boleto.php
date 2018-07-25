<?php

use PagarMe\Sdk\Transaction\BoletoTransaction;
class PagarMe_Boleto_Block_Info_Boleto extends Mage_Payment_Block_Info
{
    /**
     * @var BoletoTransaction
     */
    private $transaction;
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
        return '1';
    }

    public function getBoletoUrl()
    {
        return 'http://pagar.me';
    }
}