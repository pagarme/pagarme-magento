<?php

namespace PagarMe\Sdk\Payable;

class Payable
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $fee;

    /**
     * @var int
     */
    private $installment;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $splitRuleId;

    /**
     * @var \DateTime
     */
    private $paymentDate;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @param array bulkAnticipationData
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
     * @return int
     * @codeCoverageIgnore
     */
    public function getInstallment()
    {
        return $this->installment;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getSplitRuleId()
    {
        return $this->splitRuleId;
    }

    /**
     * @return \DateTime
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnore
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
}
