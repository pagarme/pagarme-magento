<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\Customer\Customer;

class PostbackContext extends BasicContext
{
    use Helper\CustomerDataProvider;

    private $transaction;
    private $postbacks;
    private $postback;

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
     * @When I query for postbacks
     */
    public function iQueryForPostbacks()
    {
        sleep(1);
        $this->postbacks = self::getPagarMe()
            ->postback()
            ->getList($this->transaction);
    }

    /**
     * @Then a array of Postback must be returned
     */
    public function aArrayOfPostbackMustBeReturned()
    {
        assertContainsOnly('PagarMe\Sdk\Postback\Postback', $this->postbacks);
        assertGreaterThanOrEqual(1, count($this->postbacks));
    }

    /**
     * @When query for the first postback
     */
    public function queryForTheFirstPostback()
    {
        $this->postback = self::getPagarMe()
            ->postback()
            ->get($this->transaction, $this->postbacks[0]->getId());
    }

    /**
     * @Then the same postback must be returned
     */
    public function theSamePostbackMustBeReturned()
    {
        assertInstanceOf('PagarMe\Sdk\Postback\Postback', $this->postback);
        assertEquals($this->postbacks[0], $this->postback);
    }
}
