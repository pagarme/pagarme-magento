<?php

namespace PagarMe\SdkTest\Subscription\Request;

use PagarMe\Sdk\Subscription\Request\SubscriptionUpdate;
use PagarMe\Sdk\RequestInterface;

class SubscriptionUpdateTest extends \PHPUnit_Framework_TestCase
{
    const PATH            = 'subscriptions/123';
    const SUBSCRIPTION_ID = 123;

    const CARD_ID     = 'card_123';
    const BOLETO      = 'boleto';
    const CREDIT_CARD = 'credit_card';
    const PLAN_ID     = 'plan_123';

    /**
     * @test
     */
    public function mustPayloadBeCorrectWhenNoCardSupplied()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();
        $planMock->method('getId')->willReturn(self::PLAN_ID);

        $subscriptionMock = $this->getMockBuilder('PagarMe\Sdk\Subscription\Subscription')
            ->disableOriginalConstructor()
            ->getMock();
        $subscriptionMock->method('getId')->willReturn(self::SUBSCRIPTION_ID);
        $subscriptionMock->method('getPlan')->willReturn($planMock);
        $subscriptionMock->method('getPaymentMethod')->willReturn(self::BOLETO);
        $subscriptionMock->method('getCard')->willReturn(null);

        $subscriptionCancelRequest = new SubscriptionUpdate($subscriptionMock);

        $this->assertEquals(
            $subscriptionCancelRequest->getPayload(),
            [
                'plan'           => self::PLAN_ID,
                'payment_method' => self::BOLETO
            ]
        );
    }

    /**
     * @test
     */
    public function mustPayloadBeCorrectWhenCardSupplied()
    {
        $cardMock = $this->getMockBuilder('PagarMe\Sdk\Card\Card')
            ->disableOriginalConstructor()
            ->getMock();
        $cardMock->method('getId')->willReturn(self::CARD_ID);


        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();
        $planMock->method('getId')->willReturn(self::PLAN_ID);

        $subscriptionMock = $this->getMockBuilder('PagarMe\Sdk\Subscription\Subscription')
            ->disableOriginalConstructor()
            ->getMock();
        $subscriptionMock->method('getId')->willReturn(self::SUBSCRIPTION_ID);
        $subscriptionMock->method('getPlan')->willReturn($planMock);
        $subscriptionMock->method('getPaymentMethod')->willReturn(self::BOLETO);
        $subscriptionMock->method('getCard')->willReturn($cardMock);

        $subscriptionCancelRequest = new SubscriptionUpdate($subscriptionMock);

        $this->assertEquals(
            $subscriptionCancelRequest->getPayload(),
            [
                'plan'           => self::PLAN_ID,
                'payment_method' => self::BOLETO,
                'card_id'        => self::CARD_ID
            ]
        );
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $subscriptionMock = $this->getMockBuilder('PagarMe\Sdk\Subscription\Subscription')
            ->disableOriginalConstructor()
            ->getMock();

        $subscriptionCancelRequest = new SubscriptionUpdate($subscriptionMock);

        $this->assertEquals(
            $subscriptionCancelRequest->getMethod(),
            RequestInterface::HTTP_PUT
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $subscriptionMock = $this->getMockBuilder('PagarMe\Sdk\Subscription\Subscription')
            ->disableOriginalConstructor()
            ->getMock();
        $subscriptionMock->method('getId')->willReturn(self::SUBSCRIPTION_ID);

        $subscriptionCancelRequest = new SubscriptionUpdate($subscriptionMock);

        $this->assertEquals(
            $subscriptionCancelRequest->getPath(),
            self::PATH
        );
    }
}
