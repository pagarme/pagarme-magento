<?php

namespace PagarMe\Sdk\Transfer;

use PagarMe\Sdk\BankAccount\BankAccount;

trait TransferBuilder
{
    /**
     * @param array transferData
     * @return Transfer
     */
    private function buildTransfer($transferData)
    {
        $transferData->bank_account = new BankAccount(
            $transferData->bank_account
        );

        $transferData->funding_estimated_date = new \DateTime(
            $transferData->funding_estimated_date
        );
        $transferData->date_created = new \DateTime(
            $transferData->date_created
        );
        $transferData->funding_date = new \DateTime(
            $transferData->funding_date
        );

        return new Transfer(get_object_vars($transferData));
    }
}
