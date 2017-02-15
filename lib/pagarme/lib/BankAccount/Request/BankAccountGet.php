<?php

namespace PagarMe\Sdk\BankAccount\Request;

use PagarMe\Sdk\RequestInterface;

class BankAccountGet implements RequestInterface
{
    /**
     * @var int
     */
    private $bankAccountId;

    /**
     * @param int $bankAccountId
     */
    public function __construct($bankAccountId)
    {
        $this->bankAccountId  = $bankAccountId;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf('bank_accounts/%d', $this->bankAccountId);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
