<?php

namespace PagarMe\Sdk\Subscription;

use PagarMe\Sdk\Card\Card;
use PagarMe\Sdk\Customer\Customer;
use PagarMe\Sdk\Plan\Plan;

trait SubscriptionBuilder
{
    use \PagarMe\Sdk\Transaction\TransactionBuilder;

     /**
     * @param array $subscriptionData
     * @return Subscription
     */
    private function buildSubscription($subscriptionData)
    {
        if (is_object($subscriptionData->card)) {
            $subscriptionData->card = new Card(
                get_object_vars($subscriptionData->card)
            );
        }

        $subscriptionData->current_period_start = new \DateTime(
            $subscriptionData->current_period_start
        );
        $subscriptionData->current_period_end = new \DateTime(
            $subscriptionData->current_period_end
        );

        $subscriptionData->plan = new Plan(
            get_object_vars($subscriptionData->plan)
        );
        $subscriptionData->customer = new Customer(
            get_object_vars($subscriptionData->customer)
        );

        $subscriptionData->current_transaction = $this->buildTransaction(
            $subscriptionData->current_transaction
        );

        return new Subscription(get_object_vars($subscriptionData));
    }
}
