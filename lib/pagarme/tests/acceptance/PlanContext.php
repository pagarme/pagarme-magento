<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

class PlanContext extends BasicContext
{
    private $amount;
    private $days;
    private $name;

    private $trial;
    private $methods;
    private $charges;
    private $installments;

    private $createdPlan;
    private $plans;
    private $plan;

    const NEW_PLAN_NAME = 'Changed Plan Name';

    /**
     * @Given a :amount, :days and :name
     */
    public function planMainFields($amount, $days, $name)
    {
        $this->amount = $amount;
        $this->days   = $days;
        $this->name   = $name;
    }

    /**
     * @Given :trial, :methods, :charges, and :installments
     */
    public function planAdditionalFields($trial, $methods, $charges, $installments)
    {
        $this->trial        = null;
        $this->methods      = null;
        $this->charges      = null;
        $this->installments = null;

        if ($trial != 'null') {
            $this->trial = $trial;
        }

        if ($methods != 'null') {
            $this->methods = explode(',', $methods);
        }

        if ($charges != 'null') {
            $this->charges = $charges;
        }

        if ($installments != 'null') {
            $this->installments = $installments;
        }
    }

    /**
     * @When register the plan
     */
    public function registerThePlan()
    {
        $this->createdPlan = self::getPagarMe()
            ->plan()
            ->create(
                $this->amount,
                $this->days,
                $this->name,
                $this->trial,
                $this->methods,
                $this->charges,
                $this->installments
            );
    }

    /**
     * @Then a plan must be created
     */
    public function aPlanMustBeCreated()
    {
        assertInstanceOf(
            'PagarMe\Sdk\Plan\Plan',
            $this->createdPlan
        );
    }

    /**
     * @Then must plan contain same data
     */
    public function mustPlanContainSameData()
    {
        assertEquals($this->amount, $this->createdPlan->getAmount());
        assertEquals($this->days, $this->createdPlan->getDays());
        assertEquals($this->name, $this->createdPlan->getName());
        assertEquals($this->trial, $this->createdPlan->getTrialDays());
        assertEquals(
            $this->defaultPaymentMethods($this->methods),
            $this->createdPlan->getPaymentMethods()
        );
        assertEquals($this->charges, $this->createdPlan->getCharges());
        assertEquals(
            $this->defaultInstallmets($this->installments),
            $this->createdPlan->getInstallments()
        );
    }

    private function defaultInstallmets($installments)
    {
        if (is_null($installments)) {
            return 1;
        }

        return $installments;
    }

    private function defaultPaymentMethods($methods)
    {
        if (is_null($methods)) {
            return ['boleto', 'credit_card'];
        }

        return $methods;
    }

    /**
     * @Given a previous created plans
     */
    public function aPreviousCreatedPlans()
    {
        for ($i=0; $i < 3; $i++) {
            $this->createRandomPlan();
        }
    }

    /**
     * @When I query for plans
     */
    public function iQueryForPlans()
    {
        sleep(1);
        $this->plans = self::getPagarMe()
                ->plan()
                ->getList();
    }

    /**
     * @Then a list of Plans must be returned
     */
    public function aListOfPlansMustBeReturned()
    {
        assertContainsOnly(
            'PagarMe\Sdk\Plan\Plan',
            $this->plans
        );
        assertGreaterThanOrEqual(3, count($this->plans));
    }

    /**
     * @Given a previous created plan
     */
    public function aPreviousCreatedPlan()
    {
        $this->createdPlan = $this->createRandomPlan();
    }

    /**
     * @When I query for planId
     */
    public function iQueryForPlanid()
    {
        $this->plan = self::getPagarMe()
                ->plan()
                ->get(
                    $this->createdPlan->getId()
                );
    }

    /**
     * @Then the same plan must be returned
     */
    public function theSamePlanMustBeReturned()
    {
        assertEquals(
            $this->createdPlan,
            $this->plan
        );
    }

    private function createRandomPlan()
    {
        return self::getPagarMe()
                ->plan()
                ->create(
                    rand(1000, 10000),
                    rand(10, 60),
                    uniqid('plan'),
                    null,
                    null,
                    null,
                    null,
                    null
                );
    }

    /**
     * @When I edit the plan name
     */
    public function iEditThePlanName()
    {
        $plan = $this->createdPlan;

        $plan->setName(self::NEW_PLAN_NAME);

        $this->plan = self::getPagarMe()
            ->plan()
            ->update($plan);
    }

    /**
     * @Then the name must be changed
     */
    public function theNameMustBeChanged()
    {
        assertEquals(
            self::NEW_PLAN_NAME,
            $this->plan->getName()
        );
    }
}
