<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\RequestInterface;

class SubscriptionList implements RequestInterface
{
    /**
     * @var int $page
     */

    protected $page;
    /**
     * @var int $count
     */
    protected $count;

    /**
     * @var int $page
     * @var int $count
     */
    public function __construct($page, $count)
    {
        $this->page = $page;
        $this->count = $count;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'page'  => $this->page,
            'count' => $this->count
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'subscriptions';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
