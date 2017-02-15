<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\Customer\Customer;

class BalanceOperationContext extends BasicContext
{
    use Helper\CustomerDataProvider;

    private $balanceOperation;

    /**
     * @Given a transaction with installments
     */
    public function aTransactionWithInstallments()
    {
        $customer = new Customer($this->getValidCustomerData());

        $creditCard = self::getPagarMe()
            ->card()
            ->create('4929123093547008', 'Joao Silva', '1020');

        self::getPagarMe()
            ->transaction()
            ->creditCardTransaction(
                rand(10000, 50000),
                $creditCard,
                $customer
            );
    }

    /**
     * @When I query for balance operations
     */
    public function iQueryForBalanceOperations()
    {
        $this->balanceOperation = self::getPagarMe()
            ->balanceOperation()
            ->getList(null, null, 'waiting_funds');
    }

    /**
     * @Then a list of balance operations must be returned
     */
    public function aListOfPayablesMustBeReturned()
    {
        assertContainsOnly(
            'PagarMe\Sdk\BalanceOperation\Operation',
            $this->balanceOperation
        );

        assertGreaterThanOrEqual(
            1,
            count($this->balanceOperation)
        );
    }
}
