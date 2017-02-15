<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\Subscription\Subscription;
use PagarMe\Sdk\RequestInterface;

class SubscriptionCancel implements RequestInterface
{
    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @var Subscription $subscription
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
        return sprintf('subscriptions/%d/cancel', $this->subscription->getId());
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
