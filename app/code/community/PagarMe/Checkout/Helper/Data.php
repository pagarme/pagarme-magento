<?php

use PagarMe\Sdk\Transaction\CreditCardTransaction;

class PagarMe_Checkout_Helper_Data extends Mage_Core_Helper_Abstract
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

            if (isset($paymentData['pagarme_checkout_token'])
                && $paymentData['pagarme_checkout_token'] != ''
            ) {
                $this->transaction = Mage::getModel(
                    'pagarme_core/sdk_adapter'
                )->getPagarMeSdk()
                ->transaction()
                ->get($paymentData['pagarme_checkout_token']);

                return $this->transaction;
            }
        } catch (Exception $exception) {
            Mage::logException($exception->getMessage());
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
            return PagarMe_Checkout_Block_Info_Checkout::PAYMENT_METHOD_CREDIT_CARD_LABEL;
        }

        return PagarMe_Checkout_Block_Info_Checkout::PAYMENT_METHOD_BOLETO_LABEL;
    }
}
