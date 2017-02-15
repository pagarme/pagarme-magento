<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\Plan\Plan;
use PagarMe\Sdk\Customer\Customer;

class BoletoSubscriptionCreate extends SubscriptionCreate
{
    const PAYMENT_METHOD = 'boleto';

    /**
     * @var Plan $plan
     * @var Customer $customer
     * @var string $postbackUrl
     * @var array $metadata
     */
    public function __construct(
        Plan $plan,
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

        $this->paymentMethod = self::PAYMENT_METHOD;
    }
}
