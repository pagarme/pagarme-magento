<?php

namespace PagarMe\SdkTest\Subscription\Request;

use PagarMe\Sdk\Subscription\Request\SubscriptionGet;
use PagarMe\Sdk\RequestInterface;

class SubscriptionGetTest extends \PHPUnit_Framework_TestCase
{
    const PATH            = 'subscriptions/123';
    const SUBSCRIPTION_ID = 123;

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $subscriptionGetRequest = new SubscriptionGet(self::SUBSCRIPTION_ID);

        $this->assertEquals(
            $subscriptionGetRequest->getPayload(),
            []
        );
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $subscriptionGetRequest = new SubscriptionGet(self::SUBSCRIPTION_ID);

        $this->assertEquals(
            $subscriptionGetRequest->getMethod(),
            RequestInterface::HTTP_GET
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $subscriptionGetRequest = new SubscriptionGet(self::SUBSCRIPTION_ID);

        $this->assertEquals(
            $subscriptionGetRequest->getPath(),
            self::PATH
        );
    }
}
