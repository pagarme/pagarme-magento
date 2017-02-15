<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Subscription\Subscription;

class SubscriptionUpdate implements RequestInterface
{
    /**
     * @var Subscription $subscription
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
        $payload = [
            'plan'           => $this->subscription->getPlan()->getId(),
            'payment_method' => $this->subscription->getPaymentMethod()
        ];

        $card = $this->subscription->getCard();
        if ($card instanceof \PagarMe\Sdk\Card\Card) {
            $payload['card_id'] = $this->subscription->getCard()->getId();
        }

        return $payload;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf('subscriptions/%d', $this->subscription->getId());
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_PUT;
    }
}
