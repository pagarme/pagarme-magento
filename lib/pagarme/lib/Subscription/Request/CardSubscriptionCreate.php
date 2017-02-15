<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\Card\Card;
use PagarMe\Sdk\Plan\Plan;
use PagarMe\Sdk\Customer\Customer;

class CardSubscriptionCreate extends SubscriptionCreate
{
    const PAYMENT_METHOD = 'credit_card';

    /**
     * @var Card $card
     */
    protected $card;

    /**
     * @var Plan $plan
     * @var Card $card
     * @var Customer $customer
     * @var string $postbackUrl
     * @var array $metadata
     */
    public function __construct(
        Plan $plan,
        Card $card,
        Customer $customer,
        $postbackUrl,
        $metadata
    ) {
        parent::__construct(
            $plan,
            $customer,
            $postbackUrl,
            $metadata
        );

        $this->card          = $card;
        $this->paymentMethod = self::PAYMENT_METHOD;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return array_merge(
            ['card_id' => $this->card->getId()],
            parent::getPayload()
        );
    }
}
