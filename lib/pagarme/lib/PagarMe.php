<?php

namespace PagarMe\Sdk;

use GuzzleHttp\Client as GuzzleClient;
use PagarMe\Sdk\Customer\CustomerHandler;
use PagarMe\Sdk\Transaction\TransactionHandler;
use PagarMe\Sdk\Card\CardHandler;
use PagarMe\Sdk\Calculation\CalculationHandler;
use PagarMe\Sdk\Recipient\RecipientHandler;
use PagarMe\Sdk\Plan\PlanHandler;
use PagarMe\Sdk\SplitRule\SplitRuleHandler;
use PagarMe\Sdk\Transfer\TransferHandler;
use PagarMe\Sdk\Company\CompanyHandler;
use PagarMe\Sdk\BankAccount\BankAccountHandler;
use PagarMe\Sdk\Subscription\SubscriptionHandler;
use PagarMe\Sdk\BulkAnticipation\BulkAnticipationHandler;
use PagarMe\Sdk\Payable\PayableHandler;
use PagarMe\Sdk\Zipcode\ZipcodeHandler;
use PagarMe\Sdk\BalanceOperation\BalanceOperationHandler;
use PagarMe\Sdk\Postback\PostbackHandler;
use PagarMe\Sdk\AntifraudAnalysis\AntifraudAnalysisHandler;
use PagarMe\Sdk\Search\SearchHandler;
use PagarMe\Sdk\Balance\BalanceHandler;

class PagarMe
{
    /**
     * @param Client
     */
    private $client;

    /**
     * @param CustomerHandler
     */
    private $customerHandler;

    /**
     * @param TransactionHandler
     */
    private $transactionHandler;

    /**
     * @param CardHandler
     */
    private $cardHandler;

    /**
     * @param CalculationHandler
     */
    private $calculationHandler;

    /**
     * @param RecipientHandler
     */
    private $recipientHandler;

    /**
     * @param PlanHandler
     */
    private $planHandler;

    /**
     * @param SplitRuleHandler
     */
    private $splitRuleHandler;

    /**
     * @param TransferHandler
     */
    private $transferHandler;

    /**
     * @param CompanyHandler | Manipulador de companhia
     */
    private $companyHandler;

    /**
     * @param BankAccount
     */
    private $bankAccountHandler;

    /**
     * @param SubscriptionHandler
     */
    private $subscriptionHandler;

    /**
     * @param BulkAnticipation
     */
    private $bulkAnticipationHandler;

    /**
     * @param PayableHandler
     */
    private $payableHandler;

    /**
     * @param ZipcodeHandler
     */
    private $zipcodeHandler;

    /**
     * @param BalanceOperationHandler
     */
    private $balanceOperationHandler;

    /**
     * @param PostbackHandler
     */
    private $postbackHandler;

    /**
     * @param antifraudAnalysisHandler
     */
    private $antifraudAnalysisHandler;

    /**
     * @param SearchHandler
     */
    private $searchHandler;

    /**
     * @param BalanceHandler
     */
    private $balanceHandler;

    /**
     * @param string $apiKey
     * @param int|null $timeout
     */
    public function __construct($apiKey, $timeout = null)
    {
        $this->client = new Client(
            new GuzzleClient(
                [
                    'base_url' => 'https://api.pagar.me/1/'
                ]
            ),
            $apiKey,
            $timeout
        );
    }

    /**
     * @return CustomerHandler
     */
    public function customer()
    {
        if (!$this->customerHandler instanceof CustomerHandler) {
            $this->customerHandler = new CustomerHandler($this->client);
        }

        return $this->customerHandler;
    }

    /**
     * @return TransactionHandler
     */
    public function transaction()
    {
        if (!$this->transactionHandler instanceof TransactionHandler) {
            $this->transactionHandler = new TransactionHandler($this->client);
        }

        return $this->transactionHandler;
    }

    /**
     * @return CardHandler
     */
    public function card()
    {
        if (!$this->cardHandler instanceof CardHandler) {
            $this->cardHandler = new CardHandler($this->client);
        }

        return $this->cardHandler;
    }

    /**
     * @return CalculationHandler
     */
    public function calculation()
    {
        if (!$this->calculationHandler instanceof CalculationHandler) {
            $this->calculationHandler = new CalculationHandler($this->client);
        }

        return $this->calculationHandler;
    }

    /**
     * @return RecipientHandler
     */
    public function recipient()
    {
        if (!$this->recipientHandler instanceof RecipientHandler) {
            $this->recipientHandler = new RecipientHandler($this->client);
        }

        return $this->recipientHandler;
    }

    /**
     * @return PlanHandler
     */
    public function plan()
    {
        if (!$this->planHandler instanceof PlanHandler) {
            $this->planHandler = new PlanHandler($this->client);
        }

        return $this->planHandler;
    }

    /**
     * @return SplitRuleHandler
     */
    public function splitRule()
    {
        if (!$this->splitRuleHandler instanceof SplitRuleHandler) {
            $this->splitRuleHandler = new SplitRuleHandler();
        }

        return $this->splitRuleHandler;
    }

    /**
     * @return transferHandler
     */
    public function transfer()
    {
        if (!$this->transferHandler instanceof TransferHandler) {
            $this->transferHandler = new TransferHandler($this->client);
        }

        return $this->transferHandler;
    }

    /**
     * @return CompanyHandler
     */
    public function company()
    {
        if (!$this->companyHandler instanceof CompanyHandler) {
            $this->companyHandler = new CompanyHandler($this->client);
        }

        return $this->companyHandler;
    }

    /**
     * @return BankAccountHandler
     */
    public function bankAccount()
    {
        if (!$this->bankAccountHandler instanceof BankAccountHandler) {
            $this->bankAccountHandler = new BankAccountHandler($this->client);
        }

        return $this->bankAccountHandler;
    }

    /**
     * @return SubscriptionHandler
     */
    public function subscription()
    {
        if (!$this->subscriptionHandler instanceof SubscriptionHandler) {
            $this->subscriptionHandler = new SubscriptionHandler($this->client);
        }

        return $this->subscriptionHandler;
    }

    /**
     * @return BulkAnticipationHandler
     */
    public function bulkAnticipation()
    {
        if (!$this->bulkAnticipationHandler instanceof BulkAnticipationHandler) {
            $this->bulkAnticipationHandler = new BulkAnticipationHandler($this->client);
        }

        return $this->bulkAnticipationHandler;
    }

    /**
     * @return PayableHandler
     */
    public function payable()
    {
        if (!$this->payableHandler instanceof PayableHandler) {
            $this->payableHandler = new PayableHandler($this->client);
        }

        return $this->payableHandler;
    }

    /**
     * @return ZipcodeHandler
     */
    public function zipcode()
    {
        if (!$this->zipcodeHandler instanceof ZipcodeHandler) {
            $this->zipcodeHandler = new ZipcodeHandler($this->client);
        }

        return $this->zipcodeHandler;
    }

    /**
     * @return BalanceOperationsHandler
     */
    public function balanceOperation()
    {
        if (!$this->balanceOperationHandler instanceof BalanceOperationHandler) {
            $this->balanceOperationHandler = new BalanceOperationHandler(
                $this->client
            );
        }

        return $this->balanceOperationHandler;
    }

    /**
     * @return postbackHandler
     */
    public function postback()
    {
        if (!$this->postbackHandler instanceof PostbackHandler) {
            $this->postbackHandler = new PostbackHandler(
                $this->client
            );
        }

        return $this->postbackHandler;
    }

    /**
     * @return antifraudAnalysisHandler
     */
    public function antifraudAnalysis()
    {
        if (!$this->antifraudAnalysisHandler instanceof AntifraudAnalysisHandler) {
            $this->antifraudAnalysisHandler = new AntifraudAnalysisHandler(
                $this->client
            );
        }

        return $this->antifraudAnalysisHandler;
    }

    /**
     * @return SearchHandler
     */
    public function search()
    {
        if (!$this->searchHandler instanceof SearchHandler) {
            $this->searchHandler = new SearchHandler(
                $this->client
            );
        }

        return $this->searchHandler;
    }

    /**
     * @return BalanceHandler
     */
    public function balance()
    {
        if (!$this->balanceHandler instanceof BalanceHandler) {
            $this->balanceHandler = new BalanceHandler(
                $this->client
            );
        }

        return $this->balanceHandler;
    }
}
