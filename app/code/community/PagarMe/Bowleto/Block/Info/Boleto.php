<?php

class PagarMe_Bowleto_Block_Info_Boleto extends Mage_Payment_Block_Info
{
    use PagarMe_Core_Block_Info_Trait;

    private $helper;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate(
            'pagarme/boleto/order_info/payment_details.phtml'
        );
        $this->helper = Mage::helper('pagarme_bowleto');
    }

    /**
     * @return int
     * @throws Exception
     */
    public function transactionId()
    {
        return $this->getTransaction()->getId();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getBoletoUrl()
    {
        return $this->getTransaction()->getBoletoUrl();
    }

    /**
     * Returns the template to be showed
     *
     * @return string
     */
    public function renderView()
    {
        try {
            $this->getTransaction();
        } catch (\Exception $exception) {
            $this->setTemplate('pagarme/form/payment_method.phtml');
        }

        return parent::renderView();
    }
}
