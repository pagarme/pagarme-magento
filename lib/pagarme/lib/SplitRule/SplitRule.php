<?php

namespace PagarMe\Sdk\SplitRule;

use PagarMe\Sdk\Recipient\Recipient;

class SplitRule
{

    use \PagarMe\Sdk\Fillable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var Recipient
     */
    private $recipient;

    /**
     * @var bool
     */
    private $chargeProcessingFee;
    /**
     * @var bool
     */
    private $liable;
    /**
     * @var int
     */
    private $percentage;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var \DateTime
     */
    private $dateCreated;
    /**
     * @var \DateTime
     */
    private $dateUpdated;

    public function __construct($ruleData)
    {
        $this->setRecipient($ruleData['recipient']);
        unset($ruleData['recipient']);

        $this->fill($ruleData);
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
     * @return Recipient
     * @codeCoverageIgnore
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param Recipient $recipient
     * @codeCoverageIgnore
     */
    public function setRecipient(Recipient $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function getChargeProcessingFee()
    {
        return $this->chargeProcessingFee;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function getLiable()
    {
        return $this->liable;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getPercentage()
    {
        return $this->percentage;
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
}
