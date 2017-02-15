<?php

namespace PagarMe\SdkTest\Subscription\Request;

use PagarMe\Sdk\Subscription\Request\SubscriptionList;
use PagarMe\Sdk\RequestInterface;

class SubscriptionListTest extends \PHPUnit_Framework_TestCase
{
    const PATH  = 'subscriptions';
    const PAGE  = 1;
    const COUNT = 10;

    public function paginationData()
    {
        return [
            [null, null],
            [10, null],
            [null, 13],
            [12, 21],
            [22, 22],
        ];
    }

    /**
     * @dataProvider paginationData
     * @test
     */
    public function mustPayloadBeCorrect($page, $count)
    {
        $subscriptionListRequest = new SubscriptionList($page, $count);

        $this->assertEquals(
            $subscriptionListRequest->getPayload(),
            [
                'page' => $page,
                'count' => $count
            ]
        );

        $this->assertEquals(
            $subscriptionListRequest->getMethod(),
            RequestInterface::HTTP_GET
        );

        $this->assertEquals(
            $subscriptionListRequest->getPath(),
            self::PATH
        );
    }
}
