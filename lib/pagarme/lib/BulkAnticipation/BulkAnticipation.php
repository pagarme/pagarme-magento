<?php

namespace PagarMe\Sdk\BulkAnticipation;

class BulkAnticipation
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $anticipationFee;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var \DateTime
     */
    private $dateUpdated;

    /**
     * @var int
     */
    private $fee;

    /**
     * @var \DateTime
     */
    private $paymentDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $timeframe;

    /**
     * @var string
     */
    private $type;

    /**
     * @param array $bulkAnticipationData
     */
    public function __construct($bulkAnticipationData)
    {
        $this->fill($bulkAnticipationData);
    }


    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getAnticipationFee()
    {
        return $this->anticipationFee;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
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
     * @return string
     * @codeCoverageIgnore
     */
    public function getTimeframe()
    {
        return $this->timeframe;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getType()
    {
        return $this->type;
    }
}
