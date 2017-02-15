<?php

namespace PagarMe\Sdk\Subscription;

use PagarMe\Sdk\Transaction\Transaction;
use PagarMe\Sdk\Card\Card;
use PagarMe\Sdk\Plan\Plan;

class Subscription
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var int $id
     */
    private $id;

    /**
     * @var Card $card
     */
    private $card;

    /**
     * @var Plan $plan
     */
    private $plan;

    /**
     * @var Customer $customer
     */
    private $customer;

    /**
     * @var string $postbackUrl
     */
    private $postbackUrl;

    /**
     * @var array $metadata
     */
    private $metadata;

    /**
     * @var Transaction $currentTransaction
     */
    private $currentTransaction;

    /**
     * @var string $paymentMethod
     */
    private $paymentMethod;

    /**
     * @var \DateTime $currentPeriodStart
     */
    private $currentPeriodStart;

    /**
     * @var \DateTime $currentPeriodEnd
     */
    private $currentPeriodEnd;

    /**
     * @var int $charges
     */
    private $charges;

    /**
     * @var string $status
     */
    private $status;

    /**
     * @var string $dateCreated
     */
    private $dateCreated;


    /**
     * @param array $subscriptionData
     */
    public function __construct($subscriptionData)
    {
        $this->fill($subscriptionData);
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Card
     * @codeCoverageIgnore
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param Card
     * @codeCoverageIgnore
     */
    public function setCard(Card $card)
    {
        $this->card = $card;
        return $this;
    }

    /**
     * @return Plan
     * @codeCoverageIgnore
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * @param Plan
     * @codeCoverageIgnore
     */
    public function setPlan(Plan $plan)
    {
        $this->plan = $plan;
        return $this;
    }

    /**
     * @return Customer
     * @codeCoverageIgnore
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPostbackUrl()
    {
        return $this->postbackUrl;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return Transaction
     * @codeCoverageIgnore
     */
    public function getCurrentTransaction()
    {
        return $this->currentTransaction;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string
     * @codeCoverageIgnore
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * @return DateTime
     * @codeCoverageIgnore
     */
    public function getCurrentPeriodStart()
    {
        return $this->currentPeriodStart;
    }

    /**
     * @return DateTime
     * @codeCoverageIgnore
     */
    public function getCurrentPeriodEnd()
    {
        return $this->currentPeriodEnd;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getCharges()
    {
        return $this->charges;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
}
