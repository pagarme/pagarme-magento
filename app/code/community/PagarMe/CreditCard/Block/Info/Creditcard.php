<?php

class PagarMe_CreditCard_Block_Info_Creditcard extends Mage_Payment_Block_Info_Cc
{

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
     * @deprecated
     * @see \PagarMe_Core_Block_Info_Trait::getTransaction()
     */
    public function getTransaction()
    {
        $pagarmeDbTransaction = $this->getPagePagarmeDbTransaction();
        return $this
            ->fetchPagarmeTransactionFromAPi(
                $pagarmeDbTransaction->getTransactionId()
            );
    }

    /**
     * @deprecated
     * @see \PagarMe_Core_Block_Info_Trait::getTransactionIdFromDb()
     */
    private function getPagePagarmeDbTransaction()
    {
        $order = $this->getInfo()->getOrder();
        
        if (is_null($order)) { 
            throw new Exception('Order doesn\'t exist');
        }

        return \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $order->getId()
            );
    }

    /**
     * @deprecated
     * @see \PagarMe_Core_Block_Info_Trait::fetchPagarmeTransactionFromAPi()
     */
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
        try {
            $this->getTransaction();

            return parent::renderView();
        } catch (Exception $e) {
            return '';
        }
    }
}
