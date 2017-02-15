<?php

namespace PagarMe\Sdk\Transfer\Request;

use PagarMe\Sdk\RequestInterface;

class TransferGet implements RequestInterface
{
    /**
     * @var int
     */
    private $transferId;

    /**
     * @param int $transferId
     */
    public function __construct($transferId)
    {
        $this->transferId = $transferId;
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
        return sprintf('transfers/%d', $this->transferId);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
