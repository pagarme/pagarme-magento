<?php

namespace PagarMe\Sdk\Balance;

class Balance
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var int
     */
    protected $waitingFunds;

    /**
     * @var int
     */
    protected $available;

    /**
     * @var int
     */
    protected $transferred;

    /**
     * @param array $recipientData
     */
    public function __construct($recipientData)
    {
        $this->fill($recipientData);
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getWaitingFunds()
    {
        return $this->waitingFunds;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getTransferred()
    {
        return $this->transferred;
    }
}
