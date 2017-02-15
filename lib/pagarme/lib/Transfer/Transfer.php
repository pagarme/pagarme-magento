<?php

namespace PagarMe\Sdk\Transfer;

class Transfer
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
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $fee;

    /**
     * @var \DateTime
     */
    private $fundingEstimatedDate;

    /**
     * @var PagarMe\Sdk\BankAccount\BankAccount
     */
    private $bankAccount;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var string
     */
    private $sourceType;

    /**
     * @var string
     */
    private $sourceId;

    /**
     * @var string
     */
    private $targetType;

    /**
     * @var int
     */
    private $targetId;

    /**
     * @var \DateTime
     */
    private $fundingDate;


    /**
     * @param array $arrayData
     */
    public function __construct($arrayData)
    {
        $this->fill($arrayData);
    }

    /**
     * @return int $id
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int $amount
     * @codeCoverageIgnore
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string $type
     * @codeCoverageIgnore
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string $status
     * @codeCoverageIgnore
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int $fee
     * @codeCoverageIgnore
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getFundingEstimatedDate()
    {
        return $this->fundingEstimatedDate;
    }

    /**
     * @return PagarMe\Sdk\BankAccount\BankAccount $bankAccount
     * @codeCoverageIgnore
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
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
     * @return string
     * @codeCoverageIgnore
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getTargetType()
    {
        return $this->targetType;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getTargetID()
    {
        return $this->targetId;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getFundingDate()
    {
        return $this->fundingDate;
    }
}
