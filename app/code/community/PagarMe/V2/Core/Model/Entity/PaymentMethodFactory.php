<?php

use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\Transaction\BoletoTransaction;

class PagarMe_V2_Core_Model_Entity_PaymentMethodFactory
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
            'pagarme_v2_payment_method'
        );
        
        if ($paymentMethod === PagarMe_V2_Checkout_Model_Checkout::PAGARME_CHECKOUT_CREDIT_CARD) {
            $transaction = new CreditCardTransaction([
                'token' => $infoInstance->getAdditionalInformation('token'),
                'amount' => Mage::helper('pagarme_v2_core')
                    ->parseAmountToInteger($amount),
                'postback_url' => Mage::getUrl('pagarme/transaction_creditcard/postback'),
            ]);
            
            return $transaction;
        }
        
        if ($paymentMethod === PagarMe_V2_Checkout_Model_Checkout::PAGARME_CHECKOUT_BOLETO) {
            $transaction = new BoletoTransaction([
                'token' => $infoInstance->getAdditionalInformation('token'),
                'amount' => Mage::helper('pagarme_v2_core')
                    ->parseAmountToInteger($amount),
                'postback_url' => Mage::getUrl('pagarme/transaction_boleto/postback'),
            ]);
            
            return $transaction;
        }
        
        throw new Exception('Unsupported payment method: '.$paymentMethod);
    }
}
