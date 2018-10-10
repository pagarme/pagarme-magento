<?php

use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\Transaction\BoletoTransaction;

class PagarMe_Core_Model_Entity_PaymentMethodFactory
{
    /**
     * @param float $amount
     * @param Mage_Sales_Model_Order_Payment $infoInstance
     *
     * @throws Exception
     *
     * @return \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    public function createTransactionObject(
        $amount,
        $infoInstance
    ) {
        $paymentMethod = $infoInstance->getAdditionalInformation(
            'pagarme_payment_method'
        );

        if ($paymentMethod === PagarMe_Modal_Model_Modal::PAGARME_MODAL_CREDIT_CARD) {
            $transaction = new CreditCardTransaction([
                'token' => $infoInstance->getAdditionalInformation('token'),
                'amount' => Mage::helper('pagarme_core')
                    ->parseAmountToCents($amount),
                'postback_url' => Mage::getUrl('pagarme/transaction_creditcard/postback'),
            ]);

            return $transaction;
        }

        if ($paymentMethod === PagarMe_Modal_Model_Modal::PAGARME_MODAL_BOLETO) {
            $transaction = new BoletoTransaction([
                'token' => $infoInstance->getAdditionalInformation('token'),
                'amount' => Mage::helper('pagarme_core')
                    ->parseAmountToCents($amount),
                'postback_url' => Mage::getUrl('pagarme/transaction_boleto/postback'),
            ]);

            return $transaction;
        }

        throw new Exception('Unsupported payment method: '.$paymentMethod);
    }
}
