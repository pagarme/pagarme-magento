<?php

namespace PagarMe\Sdk\BulkAnticipation\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\BulkAnticipation\BulkAnticipation;

class BulkAnticipationDelete implements RequestInterface
{
    /**
     * @var Recipient
     */
    private $recipient;

    /**
     * @var BulkAnticipation
     */
    private $bulkAnticipation;

    /**
     * @param Recipient $recipient
     */
    public function __construct(
        Recipient $recipient,
        BulkAnticipation $bulkAnticipation
    ) {
        $this->recipient        = $recipient;
        $this->bulkAnticipation = $bulkAnticipation;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_DELETE;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf(
            'recipients/%s/bulk_anticipations/%s/delete',
            $this->recipient->getId(),
            $this->bulkAnticipation->getId()
        );
    }
}
