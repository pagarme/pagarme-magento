<?php

namespace PagarMe\Sdk\BalanceOperation;

class Operation
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var int
     */
    protected $balanceAmount;

    /**
     * @var int
     */
    protected $balanceOldAmount;

    /**
     * @var string
     */
    protected $movementType;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var int
     */
    protected $fee;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var Movement
     */
    protected $movement;

    public function __construct($recipientData)
    {
        $this->fill($recipientData);
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
    public function getBalanceAmount()
    {
        return $this->balanceAmount;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getBalanceOldAmount()
    {
        return $this->balanceOldAmount;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getMovementType()
    {
        return $this->movementType;
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
    public function getFee()
    {
        return $this->fee;
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
     * @return Movement
     * @codeCoverageIgnore
     */
    public function getMovement()
    {
        return $this->movement;
    }
}
