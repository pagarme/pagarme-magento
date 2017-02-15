<?php

namespace PagarMe\Sdk\Event;

class Event
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $model;

    /**
     * @var int
     */
    private $modelId;

    /**
     * @var object
     */
    private $payload;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var \DateTime
     */
    private $dateUpdated;

    public function __construct($eventData)
    {
        $this->fill($eventData);
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
     * @return string
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
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
     * @return object
     * @codeCoverageIgnore
     */
    public function getPayload()
    {
        return $this->payload;
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
}
