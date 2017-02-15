<?php

namespace PagarMe\Sdk\Recipient\Request;

use PagarMe\Sdk\RequestInterface;

class RecipientGet implements RequestInterface
{

    /**
     * @var int
     */
    private $recipientId;

    /**
     * @param int $recipientId
     */
    public function __construct($recipientId)
    {
        $this->recipientId  = $recipientId;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf(
            'recipients/%s',
            $this->recipientId
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
