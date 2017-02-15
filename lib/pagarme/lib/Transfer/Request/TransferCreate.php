<?php

namespace PagarMe\Sdk\Transfer\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\BankAccount\BankAccount;

class TransferCreate implements RequestInterface
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @var Recipient
     */
    private $recipient;

    /**
     * @var \PagarMe\Sdk\BankAccount\BankAccount
     */
    private $bankAccount;

    /**
     * @param int $amount
     * @param Recipient $recipient
     * @param BankAccount $bankAccount Optional. Default value: null.
     */
    public function __construct(
        $amount,
        Recipient $recipient,
        BankAccount $bankAccount = null
    ) {
        $this->amount      = $amount;
        $this->recipient   = $recipient;
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $bankAccountId = null;
        if ($this->bankAccount instanceof BankAccount) {
            $bankAccountId = $this->bankAccount->getId();
        }

        return [
            'amount'          => $this->amount,
            'recipient_id'    => $this->recipient->getId(),
            'bank_account_id' => $bankAccountId
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'transfers';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
