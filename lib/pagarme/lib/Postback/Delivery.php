<?php

namespace PagarMe\Sdk\Postback;

class Delivery
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var \DateTime
     */
    private $dateUpdated;

    /**
     * @var string
     */
    private $responseBody;

    /**
     * @var string
     */
    private $responseHeaders;

    /**
     * @var int
     */
    private $responseTime;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $statusReason;

    /**
     * @param array $postbackDeliveryData
     */
    public function __construct($postbackDeliveryData)
    {
        $this->fill($postbackDeliveryData);
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getStatusReason()
    {
        return $this->statusReason;
    }
}
