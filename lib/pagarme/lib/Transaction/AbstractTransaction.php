<?php

namespace PagarMe\Sdk\Transaction;

use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\SplitRule\SplitRule;

abstract class AbstractTransaction
{
    use \PagarMe\Sdk\Fillable;

    const PROCESSING      = 'processing';
    const AUTHORIZED      = 'authorized';
    const PAID            = 'paid';
    const REFUNDED        = 'refunded';
    const WAITING_PAYMENT = 'waiting_payment';
    const PENDING_REFUND  = 'pending_refund';
    const REFUSED         = 'refused';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $refuseReason;

    /**
     * @var string
     */
    protected $statusReason;

    /**
     * @var string
     */
    protected $acquirerName;

    /**
     * @var string
     */
    protected $acquirerResponseCode;

    /**
     * @var string
     */
    protected $authorizationCode;

    /**
     * @var string
     */
    protected $softDescriptor;

    /**
     * @var string
     */
    protected $tid;

    /**
     * @var string
     */
    protected $nsu;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var \DateTime
     */
    protected $dateUpdated;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var int
     */
    protected $cost;

    /**
     * @var string
     */
    protected $postbackUrl;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var int
     */
    protected $antifraudScore;

    /**
     * @var string
     */
    protected $referer;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var int
     */
    protected $subscriptionId;

    /**
     * @var \PagarMe\Sdk\Customer\Phone
     */
    protected $phone;

    /**
     * @var \PagarMe\Sdk\Customer\Address
     */
    protected $address;

    /**
     * @var \PagarMe\Sdk\Customer\Customer
     */
    protected $customer;

    /**
     * @var array
     */
    protected $metadata;

    /**
     * @var int
     */
    protected $paidAmount;

    /**
     * @var int
     */
    protected $refundedAmount;

    /**
     * @var \PagarMe\Sdk\SplitRule\SplitRuleCollection
     */
    protected $splitRules;

    /**
     * @param array $transactionData
     */
    public function __construct($transactionData)
    {
        $this->fill($transactionData);
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
    public function getRefuseReason()
    {
        return $this->refuseReason;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getStatusReason()
    {
        return $this->statusReason;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getAcquirerName()
    {
        return $this->acquirerName;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getAcquirerResponseCode()
    {
        return $this->acquirerResponseCode;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getSoftDescriptor()
    {
        return $this->softDescriptor;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getNsu()
    {
        return $this->nsu;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getCost()
    {
        return $this->cost;
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
     * @return string
     * @codeCoverageIgnore
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getAntifraudScore()
    {
        return $this->antifraudScore;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * @return \PagarMe\Sdk\Customer\Phone
     * @codeCoverageIgnore
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return \PagarMe\Sdk\Customer\Address
     * @codeCoverageIgnore
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return \PagarMe\Sdk\Customer\Customer
     * @codeCoverageIgnore
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return PagarMe\Sdk\Card\Card
     * @codeCoverageIgnore
     */
    public function getCard()
    {
        return $this->card;
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
     * @return int
     * @codeCoverageIgnore
     */
    public function getPaidAmount()
    {
        return $this->paidAmount;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getRefundedAmount()
    {
        return $this->refundedAmount;
    }

    /**
     * @return boolean
     */
    public function isProcessing()
    {
        return $this->status == self::PROCESSING;
    }

    /**
     * @return boolean
     */
    public function isAuthorized()
    {
        return $this->status == self::AUTHORIZED;
    }

    /**
     * @return boolean
     */
    public function isPaid()
    {
        return $this->status == self::PAID;
    }

    /**
     * @return boolean
     */
    public function isRefunded()
    {
        return $this->status == self::REFUNDED;
    }

    /**
     * @return boolean
     */
    public function isWaitingPayment()
    {
        return $this->status == self::WAITING_PAYMENT;
    }

    /**
     * @return boolean
     */
    public function isPendingRefund()
    {
        return $this->status == self::PENDING_REFUND;
    }

    /**
     * @return boolean
     */
    public function isRefused()
    {
        return $this->status == self::REFUSED;
    }

    /**
     * @return \PagarMe\Sdk\SplitRule\SplitRuleCollection
     * @codeCoverageIgnore
     */
    public function getSplitRules()
    {
        return $this->splitRules;
    }
}
