<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\Customer\Customer;

class AntifraudAnalysisContext extends BasicContext
{
    use Helper\CustomerDataProvider;

    private $transaction;
    private $analyses;

    /**
     * @Given a previous created transaction
     */
    public function aPreviousCreatedTransaction()
    {
        $creditCard = self::getPagarMe()
            ->card()
            ->create('5166190508027271', 'Joao Silva', '1223');

        $customerData = $this->getValidCustomerData();
        $customer = new Customer($customerData);

        $this->transaction = self::getPagarMe()
            ->transaction()
            ->creditCardTransaction(
                1337,
                $creditCard,
                $customer,
                rand(2, 12),
                true,
                'http://eduardo.com'
            );
    }

    /**
     * @When I query transaction antifraud analyses
     */
    public function iQueryTransactionAntifraudAnalysis()
    {
        sleep(1);
        $this->analyses = self::getPagarMe()
            ->antifraudAnalysis()
            ->getList($this->transaction);
    }

    /**
     * @Then a array of Antifraud Analysis must be returned
     */
    public function aArrayOfAntifraudAnalysisMustBeReturned()
    {
        assertContainsOnly(
            'PagarMe\Sdk\AntifraudAnalysis\AntifraudAnalysis',
            $this->analyses
        );
        assertGreaterThanOrEqual(1, $this->analyses);
    }

    /**
     * @When query for the first antifraud analysis
     */
    public function queryForTheFirstAntifraudAnalysis()
    {
        $this->analysis = self::getPagarMe()
            ->antifraudAnalysis()
            ->get($this->transaction, $this->analyses[0]->getId());
    }

    /**
     * @Then the same antifraud analysis must be returned
     */
    public function theSameAntifraudAnalysisMustBeReturned()
    {
        assertInstanceOf(
            'PagarMe\Sdk\AntifraudAnalysis\AntifraudAnalysis',
            $this->analysis
        );
        assertGreaterThanOrEqual($this->analyses[0], $this->analysis);
    }
}
