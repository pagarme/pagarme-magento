<?php

namespace PagarMe\SdkTest\Subscription\Request;

use PagarMe\Sdk\Subscription\Request\SubscriptionCancel;
use PagarMe\Sdk\RequestInterface;

class SubscriptionCancelTest extends \PHPUnit_Framework_TestCase
{
    const PATH            = 'subscriptions/123/cancel';
    const SUBSCRIPTION_ID = 123;

    private $subscriptionMock;

    public function setup()
    {
        $this->subscriptionMock = $this->getMockBuilder('PagarMe\Sdk\Subscription\Subscription')
            ->disableOriginalConstructor()
            ->getMock();
        $this->subscriptionMock->method('getId')->willReturn(self::SUBSCRIPTION_ID);
    }

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $subscriptionCancelRequest = new SubscriptionCancel($this->subscriptionMock);

        $this->assertEquals(
            $subscriptionCancelRequest->getPayload(),
            []
        );
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $subscriptionCancelRequest = new SubscriptionCancel($this->subscriptionMock);

        $this->assertEquals(
            $subscriptionCancelRequest->getMethod(),
            RequestInterface::HTTP_POST
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $subscriptionCancelRequest = new SubscriptionCancel($this->subscriptionMock);

        $this->assertEquals(
            $subscriptionCancelRequest->getPath(),
            self::PATH
        );
    }
}
