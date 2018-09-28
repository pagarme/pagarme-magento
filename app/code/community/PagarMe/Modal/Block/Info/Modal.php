<?php

use \PagarMe\Sdk\Transaction\CreditCardTransaction as PagarMeCcTransaction;

class PagarMe_Modal_Block_Info_Modal extends Mage_Payment_Block_Info
{
    /**
     * @var \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    protected $transaction;

    private $helper;

    const PAYMENT_METHOD_CREDIT_CARD_LABEL = 'Cartão de Crédito';
    const PAYMENT_METHOD_BOLETO_LABEL = 'Boleto';

    public function _construct()
    {
        parent::_construct();

        if (Mage::app()->getStore()->isAdmin()) {
            $this->setTemplate(
                'pagarme/modal/order_info/payment_details.phtml'
            );
        }
        $this->helper = Mage::helper('pagarme_modal');
    }

    /**
     * @codeCoverageIgnore
     *
     * @return PagarMe_Core_Model_Transaction
     */
    public function getTransaction()
    {
        $order = $this->getInfo()->getOrder();

        if (is_null($this->transaction) && !is_null($order)) {
            $this->transaction = \Mage::getModel('pagarme_core/service_order')
                ->getTransactionByOrderId(
                    $order->getId()
                );

            return $this->transaction;
        }

        $additionalInformation = $this->getInfo()->getAdditionalInformation();

        if (is_array($additionalInformation)
            && isset($additionalInformation['token'])
        ) {
            $this->transaction = \Mage::getModel('pagarme_core/sdk_adapter')
                ->getPagarMeSdk()
                ->transaction()
                ->get($additionalInformation['token']);

            return $this->transaction;
        }

        throw new \Exception('Transaction was not found.');
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->transaction->getPaymentMethod()
            == PagarMeCcTransaction::PAYMENT_METHOD
            ? self::PAYMENT_METHOD_CREDIT_CARD_LABEL
            : self::PAYMENT_METHOD_BOLETO_LABEL;
    }

    /**
     * @return Varien_Object
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        $specificInformation = [];

        $transaction = $this->getTransaction();

        if (!is_null($transaction)) {
            $installments = 1;
            if ($transaction instanceof PagarMeCcTransaction) {
                $installments = $transaction->getInstallments();
                if (is_null($installments)) {
                    $installments = 1;
                }
            }

            $additionalInformation = $this->getInfo()
                ->getAdditionalInformation();

            $specificInformation = array_merge($specificInformation, [
                $this->helper->__('Payment Method') =>
                    $this->helper->__($this->getPaymentMethod()),
                $this->helper->__('Installments') => $installments
            ]);

            if ($this->getPaymentMethod() === self::PAYMENT_METHOD_CREDIT_CARD_LABEL
                && $additionalInformation['interest_rate'] > 0
            ) {
                $specificInformation[$this->helper->__('Interest Rate (%am)')] =
                    $additionalInformation['interest_rate'];
            }
        }

        return new Varien_Object($specificInformation);
    }
}
