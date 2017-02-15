<?php

namespace PagarMe\Sdk\Subscription;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Card\Card;
use PagarMe\Sdk\Customer\Customer;
use PagarMe\Sdk\Plan\Plan;
use PagarMe\Sdk\Transaction\BoletoTransaction;
use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\Transaction\UnsupportedTransaction;
use PagarMe\Sdk\Subscription\Request\CardSubscriptionCreate;
use PagarMe\Sdk\Subscription\Request\BoletoSubscriptionCreate;
use PagarMe\Sdk\Subscription\Request\SubscriptionGet;
use PagarMe\Sdk\Subscription\Request\SubscriptionList;
use PagarMe\Sdk\Subscription\Request\SubscriptionCancel;
use PagarMe\Sdk\Subscription\Request\SubscriptionUpdate;
use PagarMe\Sdk\Subscription\Request\SubscriptionTransactionsGet;

class SubscriptionHandler extends AbstractHandler
{
    use SubscriptionBuilder;

    /**
     * @param Plan $plan
     * @param Card $card
     * @param Customer $customer
     * @param string $postbackUrl
     * @param array $metadata
     */
    public function createCardSubscription(
        Plan $plan,
        Card $card,
        Customer $customer,
        $postbackUrl = null,
        $metadata = null
    ) {
        $request = new CardSubscriptionCreate(
            $plan,
            $card,
            $customer,
            $postbackUrl,
            $metadata
        );

        $response = $this->client->send($request);

        return $this->buildSubscription($response);
    }

    /**
     * @param int $id
     * @param Plan $plan
     * @param Customer $customer
     * @param string $postbackUrl
     * @param array $metadata
     */
    public function createBoletoSubscription(
        Plan $plan,
        Customer $customer,
        $postbackUrl = null,
        $metadata = null
    ) {
        $request = new BoletoSubscriptionCreate(
            $plan,
            $customer,
            $postbackUrl,
            $metadata
        );

        $response = $this->client->send($request);

        return $this->buildSubscription($response);
    }

    /**
     * @param int $subscriptionId
     */
    public function get($subscriptionId)
    {
        $request = new SubscriptionGet($subscriptionId);

        $response = $this->client->send($request);

        return $this->buildSubscription($response);
    }

    /**
     * @param int $page
     * @param int $count
     */
    public function getList($page = null, $count = null)
    {
        $request = new SubscriptionList($page, $count);

        $response = $this->client->send($request);

        $subscriptions = [];
        foreach ($response as $subscription) {
            $subscriptions[] = $this->buildSubscription($subscription);
        }

        return $subscriptions;
    }

    /**
     * @param Subscription $subscription
     */
    public function cancel(Subscription $subscription)
    {
        $request = new SubscriptionCancel($subscription);

        $response = $this->client->send($request);

        return $this->buildSubscription($response);
    }

    /**
     * @param Subscription $subscription
     */
    public function update(Subscription $subscription)
    {
        $request = new SubscriptionUpdate($subscription);

        $response = $this->client->send($request);

        return $this->buildSubscription($response);
    }

    /**
     * @param Subscription $subscription
     */
    public function transactions(Subscription $subscription)
    {
        $request = new SubscriptionTransactionsGet($subscription);

        $response = $this->client->send($request);

        $transactions = [];
        foreach ($response as $transaction) {
            $transactions[] = $this->buildTransaction($transaction);
        }

        return $transactions;
    }
}
