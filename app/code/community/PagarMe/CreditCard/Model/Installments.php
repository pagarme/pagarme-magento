<?php

class PagarMe_CreditCard_Model_Installments
{
    private $sdk;

    private $amount;
    private $interestRate;
    private $freeInstallments;
    private $maxInstallments;

    public function __construct(
        $amount,
        $installments,
        $freeInstallments = 0,
        $interestRate = 0,
        $maxInstallments = 12,
        $sdk = null
    ) {
        $this->sdk = $sdk;
        if (is_null($sdk)) {
            $this->sdk = Mage::getModel('pagarme_core/sdk_adapter')->getPagarmeSdk();
        }

        $this->amount = $amount;
        $this->installments = $installments;
        $this->freeInstallments = $freeInstallments;
        $this->interestRate = $interestRate;
        $this->maxInstallments = $maxInstallments;
    }

    private function calculate()
    {
        return $this->sdk
            ->calculation()
            ->calculateInstallmentsAmount(
                $this->amount,
                $this->interestRate,
                $this->freeInstallments,
                $this->maxInstallments
            );
    }

    public function getTotal() {
        return $this->getInstallmentTotalAmount($this->installments);
    }

    public function getInstallmentTotalAmount($installment) {
        $installments = $this->calculate();

        return $installments[$installment]['total_amount'];
    }
}
