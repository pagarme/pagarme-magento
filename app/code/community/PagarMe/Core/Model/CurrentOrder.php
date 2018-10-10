<?php

class PagarMe_Core_Model_CurrentOrder
{

    /**
     * @var \Mage_Sales_Model_Quote
     */
    private $quote;

    /**
     * @var PagarMe_Core_Model_Sdk_Adapter $sdk
     */
    private $pagarMeSdk;

    public function __construct(
        Mage_Sales_Model_Quote $quote,
        PagarMe_Core_Model_Sdk_Adapter $pagarMeSdk
    ) {
        $this->quote = $quote;
        $this->pagarMeSdk = $pagarMeSdk;
    }
    public function calculateInstallments(
        $maxInstallments,
        $freeInstallments,
        $interestRate
    ) {
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

    /**
     * @deprecated
     * @see self::productsTotalValueInCents
     *
     * @return int
     */
    public function productsTotalValueInCents()
    {
        return $this->orderGrandTotalInCents();
    }

    /**
     * GrandTotal represents the value of the shipping + cart items total
     * considering the discount amount
     *
     * @return int
     */
    public function orderGrandTotalInCents()
    {
        $total = $this->quote->getData()['grand_total'];

        return Mage::helper('pagarme_core')
            ->parseAmountToCents($total);
    }

    public function productsTotalValueInBRL()
    {
        $total = $this->productsTotalValueInCents();
        return Mage::helper('pagarme_core')->parseAmountToCurrency($total);
    }

    /**
     * May result in slowing the payment method view in the checkout
     *
     * @param int $installmentsValue
     * @param int $freeInstallments
     * @param float $interestRate
     *
     * @return float
     */
    public function rateAmountInBRL(
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
        return Mage::helper('pagarme_core')->parseAmountToCurrency(
            $installmentTotal - $this->productsTotalValueInCents()
        );
    }
}
