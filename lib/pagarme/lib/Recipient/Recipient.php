<?php

namespace PagarMe\Sdk\Recipient;

class Recipient
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var PagarMe
     */
    protected $bankAccount;

    /**
     * @var bool
     */
    protected $transferEnabled;

    /**
     * @var string
     */
    protected $lastTransfer;

    /**
     * @var string
     */
    protected $transferInterval;

    /**
     * @var int
     */
    protected $transferDay;

    /**
     * @var bool
     */
    protected $automaticAnticipationEnabled;

    /**
     * @var int
     */
    protected $anticipatableVolumePercentage;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var string
     */
    protected $dateUpdated;

    public function __construct($recipientData)
    {
        $this->fill($recipientData);
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
     * @return PagarMe\Sdk\BankAccount\BankAccount
     * @codeCoverageIgnore
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param PagarMe\Sdk\BankAccount\BankAccount
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function getTransferEnabled()
    {
        return $this->transferEnabled;
    }

    /**
     * @param bool
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function setTransferEnabled($transferEnabled)
    {
        $this->transferEnabled = $transferEnabled;
        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getLastTransfer()
    {
        return $this->lastTransfer;
    }

    /**
     * @param string
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function setLastTransfer($lastTransfer)
    {
        $this->lastTransfer = $lastTransfer;
        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getTransferInterval()
    {
        return $this->transferInterval;
    }

    /**
     * @param string
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function setTransferInterval($transferInterval)
    {
        $this->transferInterval = $transferInterval;
        return $this;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getTransferDay()
    {
        return $this->transferDay;
    }

    /**
     * @param int
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function setTransferDay($transferDay)
    {
        $this->transferDay = $transferDay;
        return $this;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function getAutomaticAnticipationEnabled()
    {
        return $this->automaticAnticipationEnabled;
    }

    /**
     * @param bool
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function setAutomaticAnticipationEnabled($automaticAnticipationEnabled)
    {
        $this->automaticAnticipationEnabled = $automaticAnticipationEnabled;
        return $this;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getAnticipatableVolumePercentage()
    {
        return $this->anticipatableVolumePercentage;
    }

    /**
     * @param int
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function setAnticipatableVolumePercentage($anticipatableVolumePercentage)
    {
        $this->anticipatableVolumePercentage = $anticipatableVolumePercentage;
        return $this;
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
