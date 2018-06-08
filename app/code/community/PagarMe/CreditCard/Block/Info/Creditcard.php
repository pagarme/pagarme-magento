<?php

class PagarMe_CreditCard_Block_Info_Creditcard extends Mage_Payment_Block_Info_Cc
{

    private $helper;
    private $transaction;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate(
            'pagarme/creditcard/order_info/payment_details.phtml'
        );
        $this->helper = Mage::helper('pagarme_creditcard');
    }

    public function transactionInstallments()
    {
        return $this->transaction->getInstallments();
    }

    public function transactionCustomerName()
    {
        $this->transaction = $this->getTransaction();
        return $this->transaction->getCustomer()->getName();
    }

    public function transactionCardBrand()
    {
        return $this->transaction->getCard()->getBrand();
    }

    /**
     * @codeCoverageIgnore
     *
     * @return PagarMe\Sdk\Transaction\CcTransaction
     */
    public function getTransaction()
    {
        $pagarmeDbTransaction = $this->getPagePagarmeDbTransaction();
        return $this
            ->fetchPagarmeTransactionFromAPi(
                $pagarmeDbTransaction->getTransactionId()
            );
    }

    private function getPagePagarmeDbTransaction()
    {
        $order = $this->getInfo()->getOrder();
        return \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $order->getId()
            );
    }

    private function fetchPagarmeTransactionFromAPi($transactionId)
    {
        return \Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk()
            ->transaction()
            ->get($transactionId);
    }

    /**
     * Render the block only if there's a transaction object
     *
     * @return string
     */
    public function renderView()
    {
        if ($this->transaction) {
            return parent::renderView();
        }
        return '';
    }
}
