<?php

namespace PagarMe\Sdk\Postback;

class Postback
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var int
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
     * @var array
     */
    private $deliveries;

    /**
     * @var string
     */
    private $headers;

    /**
     * @var string
     */
    private $model;

    /**
     * @var int
     */
    private $modelId;

    /**
     * @var \DateTime
     */
    private $nextRetry;

    /**
     * @var string
     */
    private $payload;

    /**
     * @var string
     */
    private $requestUrl;

    /**
     * @var int
     */
    private $retries;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    private $status;

    /**
     * @param array $postbackData
     */
    public function __construct($postbackData)
    {
        $this->fill($postbackData);
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getID()
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
     * @return array
     * @codeCoverageIgnore
     */
    public function getDeliveries()
    {
        return $this->deliveries;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getNextRetry()
    {
        return $this->nextRetry;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getRetries()
    {
        return $this->retries;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getStatus()
    {
        return $this->status;
    }
}
