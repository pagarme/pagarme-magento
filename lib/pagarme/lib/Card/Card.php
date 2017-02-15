<?php

namespace PagarMe\Sdk\Card;

class Card
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
     * @var string
     */
    private $brand;

    /**
     * @var string
     */
    private $holderName;

    /**
     * @var string
     */
    private $firstDigits;

    /**
     * @var string
     */
    private $lastDigits;

    /**
     * @var string
     */
    private $fingerprint;

    /**
     * @var object
     */
    private $customer;

    /**
     * @var boolean
     */
    private $valid;

    /**
     * @var string
     */
    private $hash;

    /**
     * @param array $arrayData
     */
    public function __construct($arrayData)
    {
        $this->fill($arrayData);
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
    public function getFirstDigits()
    {
        return $this->firstDigits;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getLastDigits()
    {
        return $this->lastDigits;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnoree
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return \DateTime
     * @codeCoverageIgnoree
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getHolderName()
    {
        return $this->holderName;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * @return object
     * @codeCoverageIgnore
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return boolean
     * @codeCoverageIgnore
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getHash()
    {
        return $this->hash;
    }
}
