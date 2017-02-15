<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\Customer\Customer;

class SearchContext extends BasicContext
{
    use Helper\CustomerDataProvider;

    const TRANSACTIONS = 5;

    private $search;

    /**
     * @Given a previous created transactions
     */
    public function aPreviousCreatedTransactions()
    {
        $creditCard = self::getPagarMe()
            ->card()
            ->create('5166190508027271', 'Jose Silva', '1223');

        for ($i=0; $i < self::TRANSACTIONS; $i++) {
            $customerData = $this->getValidCustomerData();
            $customer = new Customer($customerData);

            self::getPagarMe()
                ->transaction()
                ->creditCardTransaction(
                    rand(1000, 10000),
                    $creditCard,
                    $customer,
                    rand(2, 12),
                    true,
                    'http://eduardo.com',
                    ['source' => 'tests']
                );
        }
    }

    /**
     * @When I query for transactions created today
     */
    public function iQueryForTransactionsCreatedToday()
    {
        $this->search = self::getPagarMe()
            ->search()
            ->get(
                'transaction',
                [
                    'query' => [
                        'filtered' => [
                            'query' => ['match_all' => []],
                            'filter' => [
                                "and"=> [
                                    [
                                        "term"=> [
                                            "metadata.source" => "tests"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sort' => [
                        [
                            'date_created' => ['order' => 'desc']
                        ]
                    ],
                    'size' => 5,
                    'from' => 0
                ]
            );
    }

    /**
     * @Then a set of results must be returned
     */
    public function aSetOfResultsMustBeReturned()
    {
        assertGreaterThanOrEqual(self::TRANSACTIONS, $this->search->hits->hits);
    }
}
