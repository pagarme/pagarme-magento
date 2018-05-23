<?php

use PagarMe_Core_Model_CurrentOrder as CurrentOrder;

class PagarMe_Core_Model_Transaction extends Mage_Core_Model_Abstract
{

    use PagarMe_Core_Trait_ConfigurationsAccessor;

    /**
     * @return type
     */
    public function _construct()
    {
        return $this->_init('pagarme_core/transaction');
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param PagarMe\Sdk\Transaction\AbstractTransaction $transaction
     * @param Mage_Sales_Model_Order_Payment $infoInstance
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function saveTransactionInformation(
        Mage_Sales_Model_Order $order,
        PagarMe\Sdk\Transaction\AbstractTransaction $transaction,
        $infoInstance,
        $paymentData = null
    ) {
        $installments = 1;
        $rateAmount = 0;
        $interestRate = 0;
        $totalAmount = Mage::helper('pagarme_core')
            ->parseAmountToFloat($transaction->getAmount());

        if ($transaction instanceof PagarMe\Sdk\Transaction\CreditCardTransaction) {
            $installments = $transaction->getInstallments();

            $quote = Mage::getModel('sales/quote')
                ->load($order->getQuoteId());
            $pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter');
            $currentOrder = new CurrentOrder($quote, $pagarMeSdk);
            $interestRate = $this->getInterestRateStoreConfig();
            $rateAmount = $currentOrder
                ->rateAmountInBRL(
                    $installments,
                    $this->getFreeInstallmentStoreConfig(),
                    $interestRate,
                    $paymentData
                );
        }

        $this 
            ->setTransactionId($transaction->getId())
            ->setOrderId($order->getId())
            ->setInstallments($installments)
            ->setInterestRate($interestRate)
            ->setPaymentMethod($transaction::PAYMENT_METHOD)
            ->setFutureValue($totalAmount)
            ->setRateAmount($rateAmount)
            ->save();
    }
}
