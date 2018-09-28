<?php

use PagarMe\Sdk\Transaction\CreditCardTransaction;

class PagarMe_Modal_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    protected $transaction;

    /**
     * @return \PagarMe\Sdk\Transaction\AbstractTransaction|null
     */
    public function getTransaction()
    {
        try {
            $paymentData = Mage::app()
                ->getRequest()
                ->getPost('payment');

            if (isset($paymentData['pagarme_modal_token'])
                && $paymentData['pagarme_modal_token'] != ''
            ) {
                $this->transaction = Mage::getModel(
                    'pagarme_core/sdk_adapter'
                )->getPagarMeSdk()
                ->transaction()
                ->get($paymentData['pagarme_modal_token']);

                return $this->transaction;
            }
        } catch (Exception $exception) {
            Mage::log($exception->getMessage());
            Mage::logException($exception);
        }
    }

    /**
     * @return string
     */
    public function getPaymentMethodName()
    {
        if ($this->transaction->getPaymentMethod()
            === CreditCardTransaction::PAYMENT_METHOD
        ) {
            return PagarMe_Modal_Block_Info_Modal::PAYMENT_METHOD_CREDIT_CARD_LABEL;
        }

        return PagarMe_Modal_Block_Info_Modal::PAYMENT_METHOD_BOLETO_LABEL;
    }
}
