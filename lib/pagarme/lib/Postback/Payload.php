<?php

namespace PagarMe\Sdk\Postback;

class Payload
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $fingerprint;

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $oldStatus;

    /**
     * @var string
     */
    private $desiredStatus;

    /**
     * @var string
     */
    private $currentStatus;

    /**
     * @var \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    private $transaction;

    /**
     * @param array $payloadData
     */
    public function __construct($payloadData)
    {
        $this->fill($payloadData);
    }

    /**
     * @var int
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var string
     * @codeCoverageIgnore
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * @var string
     * @codeCoverageIgnore
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @var string
     * @codeCoverageIgnore
     */
    public function getOldStatus()
    {
        return $this->oldStatus;
    }

    /**
     * @var string
     * @codeCoverageIgnore
     */
    public function getDesiredStatus()
    {
        return $this->desiredStatus;
    }

    /**
     * @var string
     * @codeCoverageIgnore
     */
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }

    /**
     * @var AbstractTransaction
     * @codeCoverageIgnore
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
