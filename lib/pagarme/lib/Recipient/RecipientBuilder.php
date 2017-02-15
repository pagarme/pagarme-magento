<?php

namespace PagarMe\Sdk\Recipient;

use PagarMe\Sdk\BankAccount\BankAccount;

trait RecipientBuilder
{
    /**
     * @param array $recipientData
     * @return Recipient
     */
    private function buildRecipient($recipientData)
    {
        $recipientData->date_created = new \DateTime(
            $recipientData->date_created
        );

        $recipientData->date_updated = new \DateTime(
            $recipientData->date_updated
        );

        $recipientData->bank_account = new BankAccount(
            get_object_vars($recipientData->bank_account)
        );

        return new Recipient($recipientData);
    }
}
