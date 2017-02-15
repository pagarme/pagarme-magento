<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

class BalanceContext extends BasicContext
{
    private $balance;

    /**
     * @When I query for balance
     */
    public function iQueryForBalance()
    {
        $this->balance = self::getPagarMe()->balance()->get();
    }

    /**
     * @Then a balance must be returned
     */
    public function aBalanceMustBeReturned()
    {
        assertInstanceOf('PagarMe\Sdk\Balance\Balance', $this->balance);
    }
}
