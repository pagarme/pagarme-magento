<?php

namespace PagarMe\Sdk\Recipient\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Recipient\Recipient;

class RecipientBalanceOperations implements RequestInterface
{

    /**
     * @param Recipient
     */
    private $recipient;

    /**
     * @param int
     */
    private $page;

    /**
     * @param int
     */
    private $count;

    /**
     * @var Recipient $recipient
     * @var int $page
     * @var int $count
     */
    public function __construct(Recipient $recipient, $page, $count)
    {
        $this->recipient = $recipient;
        $this->page      = $page;
        $this->count     = $count;
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
        return sprintf(
            'recipients/%s/balance/operations',
            $this->recipient->getId()
        );
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
