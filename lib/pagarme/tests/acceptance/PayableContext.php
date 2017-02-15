<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\Customer\Customer;

class PayableContext extends BasicContext
{
    use Helper\CustomerDataProvider;

    const INSTALLMENTS = 7;

    private $payables;

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
                $customer,
                self::INSTALLMENTS
            );
    }

    /**
     * @When I query for payables
     */
    public function iQueryForPayables()
    {
        $this->payables = self::getPagarMe()
            ->payable()
            ->getList();
    }

    /**
     * @Then a list of payables must be returned
     */
    public function aListOfPayablesMustBeReturned()
    {
        assertContainsOnly(
            'PagarMe\Sdk\Payable\Payable',
            $this->payables
        );

        assertGreaterThanOrEqual(self::INSTALLMENTS, count($this->payables));
    }
}
