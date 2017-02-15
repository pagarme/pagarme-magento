<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class CalculationContext extends BasicContext
{
    private $amount;
    private $interestRate;
    private $maxInstallments;
    private $freeInstallments;
    private $installments;

    /**
     * @Given a :amount
     */
    public function aAmount($amount)
    {
        $this->amount =  $amount;
    }

    /**
     * @Given a :interestRate in :maxInstallments
     */
    public function aInterestRateInMaxInstallments($interestRate, $maxInstallments)
    {
        $this->interestRate = $interestRate;
        $this->maxInstallments = $maxInstallments;
    }

    /**
     * @When with :freeInstallments
     */
    public function withFreeInstallments($freeInstallments)
    {
        $this->freeInstallments = $freeInstallments;
    }

    /**
     * @When calculate installments
     */
    public function calculateInstallments()
    {
        $this->installments = self::getPagarMe()
            ->calculation()
            ->calculateInstallmentsAmount(
                $this->amount,
                $this->interestRate,
                $this->freeInstallments,
                $this->maxInstallments
            );
    }

    /**
     * @Then the number of calculated instalmments must be :installments
     */
    public function theNumberOfCalculatedInstalmmentsMustBe($installments)
    {
        assertEquals($installments, count($this->installments));
    }
}
