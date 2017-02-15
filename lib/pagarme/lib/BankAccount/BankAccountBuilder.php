<?php

namespace PagarMe\Sdk\BankAccount;

trait BankAccountBuilder
{
    /**
     * @param $bankAccountData
     * @return BankAccount
     */
    public function buildBankAccount($bankAccountData)
    {
        $bankAccountData->date_created = new \DateTime(
            $bankAccountData->date_created
        );

        return new BankAccount(get_object_vars($bankAccountData));
    }
}
