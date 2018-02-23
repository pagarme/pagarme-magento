<?php

class PagarMe_CreditCard_Block_Info_Creditcard extends Mage_Payment_Block_Info_Cc
{

    private $helper;
    private $transaction;

    public function _construct()
    {
        parent::_construct();

        $this->setTemplate(
            'pagarme/creditcard/order_info/payment_details.phtml'
        );
        $this->helper = Mage::helper('pagarme_creditcard');
        $this->transaction = $this->getTransaction();
    }

    public function transactionInstallments()
    {
        return $this->getTransaction()->getInstallments();
    }

    public function transactionCustomerName()
    {
        return $this->getTransaction()->getCustomer()->name;
    }

    public function transactionCardBrand()
    {
        return $this->getTransaction()->getCard()->brand;
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
}

