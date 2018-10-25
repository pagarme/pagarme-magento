<?php

class PagarMe_CreditCard_Block_Info_Creditcard extends Mage_Payment_Block_Info_Cc
{
    use PagarMe_Core_Block_Info_Trait;

    /**
     * @var PagarMe_CreditCard_Helper
     */
    private $helper;

    /**
     * @var \PagarMe\Sdk\Transaction\CreditCardTransaction
     */
    private $transaction;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate(
            'pagarme/creditcard/order_info/payment_details.phtml'
        );
        $this->helper = Mage::helper('pagarme_creditcard');
    }

    /**
     * @return string
     */
    public function transactionInstallments()
    {
        return $this->transaction->getInstallments();
    }

    /**
     * @return string
     */
    public function transactionCustomerName()
    {
        $this->transaction = $this->getTransaction();
        return $this->transaction->getCustomer()->getName();
    }

    /**
     * @return string
     */
    public function transactionCardHolderName()
    {
        return $this->transaction->getCard()->getHolderName();
    }

    /**
     * @return string
     */
    public function transactionCardBrand()
    {
        return $this->transaction->getCard()->getBrand();
    }

    /**
     * @return int
     */
    public function transactionId()
    {
        return $this->transaction->getId();
    }

    /**
     * Render the block only if there's a transaction object
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
