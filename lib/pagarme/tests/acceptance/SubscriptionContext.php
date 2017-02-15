<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\Account\Account;
use PagarMe\Sdk\Customer\Customer;
use PagarMe\Sdk\Customer\Address;
use PagarMe\Sdk\Customer\Phone;

class SubscriptionContext extends BasicContext
{
    private $customer;
    private $plan;
    private $creditCard;
    private $subscription;
    private $subscriptions;
    private $querySubscription;
    private $transactions;

    /**
     * @Given a valid customer
     */
    public function aValidCustomer()
    {
        $this->customer = self::getPagarMe()
            ->customer()
            ->create(
                'John Doe',
                'john@test.com',
                '25123317171',
                new Address(
                    [
                        'street'        => 'Rua Teste',
                        'street_number' => 123,
                        'neighborhood'  => 'Centro',
                        'zipcode'       => '01034020'
                    ]
                ),
                new Phone(
                    [
                        'ddd'    => '11',
                        'number' => '44445555'
                    ]
                )
            );
    }

    /**
     * @Given a valid plan
     */
    public function aValidPlan()
    {
        $this->plan = self::getPagarMe()
            ->plan()
            ->create(555, 30, 'Test Plan');
    }

    /**
     * @Given a valid card
     */
    public function aValidCard()
    {
        $this->creditCard = self::getPagarMe()
            ->card()
            ->create('4539706041746367', "John Doe", '0725');
    }

    /**
     * @When make a credit card subscription
     */
    public function makeACreditCardSubscription()
    {
        $this->subscription = self::getPagarMe()
            ->subscription()
            ->createCardSubscription(
                $this->plan,
                $this->creditCard,
                $this->customer
            );
    }


    /**
     * @When make a boleto subscription
     */
    public function makeABoletoSubscription()
    {
        $this->subscription = self::getPagarMe()
            ->subscription()
            ->createBoletoSubscription(
                $this->plan,
                $this->customer
            );
    }

    /**
     * @Then a subscription must be created
     */
    public function aSubscriptionMustBeCreated()
    {
        assertInstanceOf(
            'PagarMe\Sdk\Subscription\Subscription',
            $this->subscription
        );
    }

     /**
     * @Then the payment method must be :paymentMethod
     */
    public function thePaymentMethodMustBe($paymentMethod)
    {
        assertEquals($paymentMethod, $this->subscription->getPaymentMethod());
    }

    /**
     * @Given a previous created subscription
     */
    public function aPreviousCreatedSubscription()
    {
        $this->aValidCustomer();
        $this->aValidPlan();
        $this->makeABoletoSubscription();
    }

    /**
     * @When I query for the subscription
     */
    public function iQueryForTheSubscription()
    {
        $this->querySubscription = self::getPagarMe()
            ->subscription()
            ->get($this->subscription->getId());
    }

    /**
     * @Then the same subscription must be returned
     */
    public function theSameSubscriptionMustBeReturned()
    {
        assertEquals(
            $this->subscription->getId(),
            $this->querySubscription->getId()
        );
    }

    /**
     * @Given previous created subscriptions
     */
    public function previousCreatedSubscriptions()
    {
        $this->aPreviousCreatedSubscription();
        $this->aPreviousCreatedSubscription();
        $this->aPreviousCreatedSubscription();
        sleep(1);
    }

    /**
     * @When I query for subscriptions
     */
    public function iQueryForSubscriptions()
    {
        $this->subscriptions = self::getPagarMe()
            ->subscription()
            ->getList();
    }

    /**
     * @Then subscriptions must be returned
     */
    public function subscriptionsMustBeReturned()
    {
        assertGreaterThanOrEqual(3, count($this->subscriptions));
        assertContainsOnly(
            'PagarMe\Sdk\Subscription\Subscription',
            $this->subscriptions
        );
    }

    /**
     * @When I cancel the subscription
     */
    public function iCancelTheSubscription()
    {
        $this->subscription = self::getPagarMe()
            ->subscription()
            ->cancel($this->subscription);
    }

    /**
     * @Then subscription status must be :status
     */
    public function subscriptionStatusMustBe($status)
    {
        assertEquals($status, $this->subscription->getStatus());
    }

    /**
     * @When I query the transactions of this subscription
     */
    public function iQueryTheTransactionsOfThisSubscription()
    {
        sleep(1);
        $this->transactions = self::getPagarMe()
            ->subscription()
            ->transactions($this->subscription);
    }

    /**
     * @Then transactions must be returned
     */
    public function transactionsMustBeReturned()
    {
        assertGreaterThanOrEqual(1, count($this->transactions));
        assertContainsOnly(
            'PagarMe\Sdk\Transaction\AbstractTransaction',
            $this->transactions
        );
    }
}
