<?php

namespace PagarMe\Sdk\Transfer;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\BankAccount\BankAccount;
use PagarMe\Sdk\Transfer\Request\TransferCreate;
use PagarMe\Sdk\Transfer\Request\TransferGet;
use PagarMe\Sdk\Transfer\Request\TransferList;
use PagarMe\Sdk\Transfer\Request\TransferCancel;

class TransferHandler extends AbstractHandler
{
    /**
     * @param int $amount
     * @param Recipient $recipient
     * @param BankAccount $bankAccount Optional. Default: null.
     * @return Transfer
     */
    public function create(
        $amount,
        Recipient $recipient,
        BankAccount $bankAccount = null
    ) {
        $request = new TransferCreate(
            $amount,
            $recipient,
            $bankAccount
        );

        $response = $this->client->send($request);

        return $this->buildTransfer($response);
    }

    /**
     * @param int transferId
     */
    public function get($transferId)
    {
        $request = new TransferGet($transferId);

        $response = $this->client->send($request);

        return $this->buildTransfer($response);
    }

    /**
     * @param int page
     * @param int count
     */
    public function getList($page = null, $count = null)
    {
        $request = new TransferList($page, $count);

        $response = $this->client->send($request);

        $tranfers = [];

        foreach ($response as $transferData) {
            $tranfers[] = $this->buildTransfer($transferData);
        }

        return $tranfers;
    }

    /**
     * @param Transfer transfer
     */
    public function cancel(Transfer $transfer)
    {
        $request = new TransferCancel($transfer);

        $response = $this->client->send($request);

        return $this->buildTransfer($response);
    }

    /**
     * array transferData
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
