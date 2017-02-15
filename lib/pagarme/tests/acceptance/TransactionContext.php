<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\Customer\Customer;

class TransactionContext extends BasicContext
{
    use Helper\CustomerDataProvider;

    const POSTBACK_URL = 'example.com/postback';

    private $creditCard;
    private $customer;
    private $transaction;
    private $transactionList = [];
    private $events;
    private $metadata;

    /**
     * @When register a card with :number, :holder and :expiration
     */
    public function registerACardWithAnd($number, $holder, $expiration)
    {
        $this->creditCard = self::getPagarMe()
            ->card()
            ->create($number, $holder, $expiration);
    }

    /**
     * @Given a valid customer
     */
    public function aValidCustomer()
    {
        $customerData = $this->getValidCustomerData();
        $this->customer = new Customer($customerData);
    }


     /**
     * @When make a credit card transaction with :arg1 and :arg2
     * @And make a credit card transaction with :amount and :installments
     */
    public function makeACreditCardTransactionWithAnd($amount, $installments)
    {
        $this->transaction = self::getPagarMe()
            ->transaction()
            ->creditCardTransaction(
                $amount,
                $this->creditCard,
                $this->customer,
                $installments
            );
    }

    /**
     * @Given make a boleto transaction with :amount
     */
    public function makeABoletoTransactionWith($amount)
    {
        $this->transaction = self::getPagarMe()
            ->transaction()
            ->boletoTransaction($amount, $this->customer, self::POSTBACK_URL);
    }

    /**
     * @Then a valid transaction must be created
     */
    public function aValidTransactionMustBeCreated()
    {
        assertInstanceOf(
            'PagarMe\Sdk\Transaction\AbstractTransaction',
            $this->transaction
        );
    }

    /**
     * @Given make a boleto transaction with :amount, using Customers from the API
     */
    public function makeABoletoTransactionWithAGivenAmountUsingCustomersFromTheAPI($amount)
    {
        $customersIdList = $this->getCustomerIdsFromAPI();

        foreach($customersIdList as $id) {
            /** @var $customer \PagarMe\Sdk\Customer\Customer */
            $customer = self::getPagarMe()
                ->customer()
                ->get($id);

            $this->transactionList[] = self::getPagarMe()
                ->transaction()
                ->boletoTransaction($amount, $customer, self::POSTBACK_URL);
        }
    }

    /**
     * @Then a list of valid transactions must be created
     */
    public function aListOfValidTransactionsMustBeCreated()
    {
        foreach ($this->transactionList as $transaction) {
            assertInstanceOf(
                'PagarMe\Sdk\Transaction\AbstractTransaction',
                $transaction
            );
        }
    }

    /**
     * @Then a paid transaction must be created
     */
    public function aPaidTransactionMustBeCreated()
    {
        $this->aValidTransactionMustBeCreated();
        echo sprintf("TransactionId: %s\n", $this->transaction->getid());
        assertTrue($this->transaction->isPaid());
    }

    /**
     * @Given authorize a credit card transaction with :amount and :installments
     */
    public function authorizeACreditCardTransactionWithAnd($amount, $installments)
    {
        $this->transaction = self::getPagarMe()
            ->transaction()
            ->creditCardTransaction(
                $amount,
                $this->creditCard,
                $this->customer,
                $installments,
                false
            );
    }

    /**
     * @Then a authorized transaction must be created
     */
    public function aAuthorizedTransactionMustBeCreated()
    {
        $this->aValidTransactionMustBeCreated();

        $transaction = self::getPagarMe()
            ->transaction()
            ->get($this->transaction->getId());

        echo sprintf("TransactionId: %s\n", $this->transaction->getid());
        assertTrue($transaction->isAuthorized());
    }

    /**
     * @Given capture the transaction
     */
    public function captureTheTransaction($amount = null)
    {
        $transaction = $this->transaction;

        self::getPagarMe()
            ->transaction()
            ->capture($transaction, $amount);

        $this->transaction = self::getPagarMe()
            ->transaction()
            ->get($transaction->getId());
    }

    /**
     * @Given a valid card
     */
    public function aValidCard()
    {
        $this->registerACardWithAnd('4539706041746367', "John Doe", '0725');
    }

    /**
     * @Given a valid credit card transaction
     */
    public function aValidCreditCardTransaction()
    {
        $this->makeACreditCardTransactionWithAnd('1337', rand(1, 12));
    }

    /**
     * @Then then transaction must be retriavable
     */
    public function thenTransactionMustBeRetriavable()
    {
        $transaction = self::getPagarMe()
            ->transaction()
            ->get($this->transaction->getId());

        assertEquals($this->transaction->getId(), $transaction->getId());
    }

    /**
     * @Given a valid boleto transaction
     */
    public function aValidBoletoTransaction()
    {
        $this->makeABoletoTransactionWith(1337);
    }

     /**
     * @Given I had multiple transactions registered
     */
    public function iHadMultipleTransactionsRegistered()
    {
        $this->aValidCustomer();
        $this->makeABoletoTransactionWith(1337);
        $this->makeABoletoTransactionWith(486);
        $this->makeABoletoTransactionWith(8008);
    }

    /**
     * @When query transactions
     */
    public function queryTransactions()
    {
        $this->transactionList = self::getPagarMe()
            ->transaction()
            ->getList();
    }

    /**
     * @Then an array of transactions must be returned
     */
    public function anArrayOfTransactionsMustBeReturned()
    {
        assertContainsOnly('PagarMe\Sdk\Transaction\AbstractTransaction', $this->transactionList);
        assertGreaterThanOrEqual(2, count($this->transactionList));
    }

     /**
     * @Given capture the transaction with amount :amount
     */
    public function captureTheTransactionWithAmount($amount)
    {
        $this->captureTheTransaction($amount);
    }

    /**
     * @Then a paid transaction must be created with :amount paid amount
     */
    public function aPaidTransactionMustBeCreatedWithPaidAmount($amount)
    {
        $this->aPaidTransactionMustBeCreated();
        assertEquals($amount, $this->transaction->getPaidAmount());
    }

    /**
     * @Then full refund the transaction
     */
    public function fullRefundTheTransaction()
    {
        $this->transaction = $transaction = self::getPagarMe()
            ->transaction()
            ->creditCardRefund($this->transaction);
    }

    /**
     * @Then the transaction must be refunded
     * @And the transaction must be refunded
     */
    public function theTransactionMustBeRefunded()
    {
        assertTrue($this->transaction->isRefunded());
    }

    /**
     * @When refund given :amount the transaction
     */
    public function refundGivenTheTransaction($amount)
    {
        $this->transaction = $transaction = self::getPagarMe()
            ->transaction()
            ->creditCardRefund($this->transaction, $amount);
    }

    /**
     * @Then the transaction must be refunded with :amount
     */
    public function theTransactionMustBeRefundedWith($amount)
    {
        assertEquals($amount, $this->transaction->getRefundedAmount());
    }

    /**
     * @Given I had a transactions registered
     */
    public function iHadATransactionsRegistered()
    {
        $this->aValidCustomer();
        $this->aValidBoletoTransaction();
    }

    /**
     * @When query transactions events
     */
    public function queryTransactionsEvents()
    {
        $this->events = $transaction = self::getPagarMe()
            ->transaction()
            ->events($this->transaction);
    }

    /**
     * @Then an array of events must be returned
     */
    public function anArrayOfEventsMustBeReturned()
    {
        assertContainsOnly('PagarMe\Sdk\Event\Event', $this->events);
        assertGreaterThanOrEqual(1, count($this->events));
    }

    /**
     * @When make a credit card transaction with random amount and metadata
     */
    public function makeACreditCardTransactionWithRandomAmountAndMetadata()
    {
        $this->getRandomMetadata();

        $this->transaction = self::getPagarMe()
            ->transaction()
            ->creditCardTransaction(
                rand(5000, 10000),
                $this->creditCard,
                $this->customer,
                null,
                null,
                self::POSTBACK_URL,
                $this->metadata
            );
    }

    /**
     * @Then must contain same metadata
     */
    public function mustContainSameMetadata()
    {
        assertEquals($this->metadata, $this->transaction->getMetadata());
    }

    /**
     * @When make a boleto transaction with random amount and metadata
     */
    public function makeABoletoTransactionWithRandomAmountAndMetadata()
    {
        $this->getRandomMetadata();

        $this->transaction = self::getPagarMe()
            ->transaction()
            ->boletoTransaction(
                rand(5000, 10000),
                $this->customer,
                self::POSTBACK_URL,
                $this->metadata
            );
    }

    private function getRandomMetadata()
    {
        $this->metadata = [uniqid('key') => uniqid('value')];
    }

    private function getCustomerIdsFromAPI()
    {
        $ids = [];
        $customerList = self::getPagarMe()
            ->customer()
            ->getList();

        foreach ($customerList as $customer) {
            $ids[] = $customer->getId();
        }

        return $ids;
    }
}
