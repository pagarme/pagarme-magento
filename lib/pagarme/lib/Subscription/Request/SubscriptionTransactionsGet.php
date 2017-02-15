<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Subscription\Subscription;

class SubscriptionTransactionsGet implements RequestInterface
{
    /**
     * @var Subscription $subscription
     */
    protected $subscription;

    /**
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf(
            'subscriptions/%d/transactions',
            $this->subscription->getId()
        );
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
