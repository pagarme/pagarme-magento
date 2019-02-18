<?php

class PagarMe_CreditCard_Model_Installments
{
    /**
     * @var \PagarMe\Sdk\PagarMe
     */
    private $sdk;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var float
     */
    private $interestRate;

    /**
     * @var integer
     */
    private $freeInstallments;

    /**
     * @var integer
     */
    private $maxInstallments;

    /**
     * @param int $amount
     * @param int $installments
     * @param int $freeInstallments
     * @param float $interestRate
     * @param int $maxInstallments
     * @param \PagarMe\Sdk\PagarMe $sdk
     */
    public function __construct(
        $amount,
        $installments,
        $freeInstallments = 0,
        $interestRate = 0,
        $maxInstallments = 12,
        $sdk = null
    ) {
        $this->sdk = $sdk;
        $this->amount = $amount;
        $this->installments = $installments;
        $this->freeInstallments = $freeInstallments;
        $this->interestRate = $interestRate;
        $this->maxInstallments = $maxInstallments;
    }

    /**
     * @return array
     */
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

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->getInstallmentTotalAmount($this->installments);
    }

    /**
     * @param int $installment
     *
     * @return int
     */
    public function getInstallmentTotalAmount($installment)
    {
        $installments = $this->calculate();

        return $installments[$installment]['total_amount'];
    }

    /**
     * @return int
     */
    public function getRateAmount()
    {
        return intval($this->getTotal() - $this->amount);
    }
}
