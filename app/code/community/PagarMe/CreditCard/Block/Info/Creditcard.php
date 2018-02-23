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
        $this->transaction = null;
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
        if (is_null($this->transaction)) {
            $order = $this->getInfo()->getOrder();

            $pagarmeDbTransaction = \Mage::getModel('pagarme_core/service_order')
                ->getTransactionByOrderId(
                    $order->getId()
                );

            try {
                $this->transaction = \Mage::getModel('pagarme_core/sdk_adapter')
                    ->getPagarMeSdk()
                    ->transaction()
                    ->get($pagarmeDbTransaction->getTransactionId());

                return $this->transaction;
            } catch (Exception $anyException) {
                throw new \Exception('Transaction was not found.');
            }
        } else {
            return $this->transaction;
        }
    }
}

