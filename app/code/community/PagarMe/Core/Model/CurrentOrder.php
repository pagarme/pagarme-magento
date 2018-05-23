<?php

class PagarMe_Core_Model_CurrentOrder
{

    private $quote;
    private $pagarMeSdk;
    private $coreHelper;
    private $modalHelper;

    public function __construct(
        Mage_Sales_Model_Quote $quote,
        PagarMe_Core_Model_Sdk_Adapter $pagarMeSdk,
        $coreHelper = null,
        $modalHelper = null
    ) {
        $this->quote = $quote;
        $this->pagarMeSdk = $pagarMeSdk;
        $this->coreHelper = $coreHelper;
        if (is_null($coreHelper)) {
            $this->coreHelper = Mage::helper('pagarme_core');
        }
        $this->modalHelper = $modalHelper;
        if (is_null($modalHelper)) {
            $this->modalHelper = Mage::helper('pagarme_modal');
        }
    }

    public function calculateInstallments(
        $maxInstallments,
        $freeInstallments,
        $interestRate
    ){
        $amount = $this->productsTotalValueInCents();
        return $this->pagarMeSdk->getPagarMeSdk()
            ->calculation()
            ->calculateInstallmentsAmount(
                $amount,
                $interestRate,
                $freeInstallments,
                $maxInstallments
            );
    }

    //Subtotal should be the sum of all items in the cart
    //there's also Basesubtotal = subtotal in the store's currency
    public function productsTotalValueInCents()
    {
        $total = $this->quote->getTotals()['subtotal']->getValue();
        return $this->coreHelper->parseAmountToInteger($total);
    }

    public function productsTotalValueInBRL()
    {
        $total = $this->productsTotalValueInCents();
        return $this->coreHelper->parseAmountToFloat($total);
    }

    private function shippingValueInBRL()
    {
        return $this->quote->getShippingAddress()->getShippingAmount();
    }

    private function shippingValueInCents()
    {
        return $this->coreHelper->parseAmountToInteger(
            $this->shippingValueInBRL()
        );
    }

    //May result in slowing the payment method view in the checkout
    public function rateAmountInBRL(
        $installmentsValue,
        $freeInstallments,
        $interestRate,
        $paymentData = null
    ) {
        if (is_array($paymentData)) {
            if ($this->paymentIsModalCc($paymentData)) {
                return $this->interestAmountWhenTokenIsPresentInBRL();
            }
        }
        return $this->interestAmountWhenTokenIsntPresentInBRL(
            $installmentsValue,
            $freeInstallments,
            $interestRate
        );
    }

    private function paymentIsModalCc($paymentData)
    {
        $paymentMethodIsModal = array_key_exists(
            'method',
            $paymentData
        ) && $paymentData['method'] == 'pagarme_modal';
        $paymentModalPaymentMethodIsCc = array_key_exists(
            'pagarme_modal_payment_method',
            $paymentData
        ) && $paymentData['pagarme_modal_payment_method'] == 'credit_card';
        return $paymentMethodIsModal && $paymentModalPaymentMethodIsCc;
    }

    //the pagarme checkout also applies the intereset to the shipping
    private function interestAmountWhenTokenIsPresentInBRL()
    {
        $transaction = $this->modalHelper->getTransaction();
        $subtotalWithShipping = $this->productsTotalValueInCents() +
            $this->shippingValueInCents();
        return $this->coreHelper
            ->parseAmountToFloat(
                $transaction->getAmount() - $subtotalWithShipping
            );
    }

    private function interestAmountWhenTokenIsntPresentInBRL(
        $installmentsValue,
        $freeInstallments,
        $interestRate
    ) {
        $installments = $this->calculateInstallments(
            $installmentsValue,
            $freeInstallments,
            $interestRate
        );

        $installmentTotal = $installments[$installmentsValue]['total_amount'];
        return $this->coreHelper
            ->parseAmountToFloat(
                $installmentTotal - $this->productsTotalValueInCents()
            );
    }
}
