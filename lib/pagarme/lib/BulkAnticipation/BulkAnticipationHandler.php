<?php

namespace PagarMe\Sdk\BulkAnticipation;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationCreate;
use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationLimits;
use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationConfirm;
use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationCancel;
use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationDelete;
use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationList;

class BulkAnticipationHandler extends AbstractHandler
{
    /**
     * @param  Recipient $recipient
     * @param  DateTime $paymentDate
     * @param  string $timeframe
     * @param  int $requestedAmount
     * @param  boolean $building
     * @return BulkAnticipation
     */
    public function create(
        Recipient $recipient,
        \DateTime $paymentDate,
        $timeframe,
        $requestedAmount,
        $building
    ) {
        $request = new BulkAnticipationCreate(
            $recipient,
            $paymentDate,
            $timeframe,
            $requestedAmount,
            $building
        );

        $response = $this->client->send($request);

        return $this->buildBulkAnticipation($response);
    }

    /**
     * @param  Recipient $recipient
     * @param  DateTime $paymentDate
     * @param  string $timeframe
     * @return  array
     */
    public function limits($recipient, $paymentDate, $timeframe)
    {
        $request = new BulkAnticipationLimits(
            $recipient,
            $paymentDate,
            $timeframe
        );

        return $this->client->send($request);
    }

    /**
     * @param Recipient $recipient
     * @param BulkAnticipation $bulkAnticipation
     * @return BulkAnticipation
     */
    public function confirm(
        Recipient $recipient,
        BulkAnticipation $bulkAnticipation
    ) {
        $request = new BulkAnticipationConfirm($recipient, $bulkAnticipation);

        $response = $this->client->send($request);

        return $this->buildBulkAnticipation($response);
    }

    /**
     * @param Recipient $recipient
     * @param BulkAnticipation $bulkAnticipation
     * @return BulkAnticipation
     */
    public function cancel(
        Recipient $recipient,
        BulkAnticipation $bulkAnticipation
    ) {
        $request = new BulkAnticipationCancel($recipient, $bulkAnticipation);

        $response = $this->client->send($request);

        return $this->buildBulkAnticipation($response);
    }

    /**
     * @param Recipient $recipient
     * @param BulkAnticipation $bulkAnticipation
     * @return array
     */
    public function delete(
        Recipient $recipient,
        BulkAnticipation $bulkAnticipation
    ) {
        $request = new BulkAnticipationDelete($recipient, $bulkAnticipation);

        $response = $this->client->send($request);

        return $this->buildBulkAnticipation($response);
    }

    /**
     * @param Recipient $recipient
     * @param int $page
     * @param int $count
     * @return array
     */
    public function getList(Recipient $recipient, $page = null, $count = null)
    {
        $request = new BulkAnticipationList($recipient, $page, $count);

        $response = $this->client->send($request);

        $bulkAnticipations = [];

        foreach ($response as $bulkAnticipationData) {
            $bulkAnticipations[] = new BulkAnticipation(
                get_object_vars($bulkAnticipationData)
            );
        }

        return $bulkAnticipations;
    }

    /**
     * @param array $bulkAnticipationData
     * @return BulkAnticipation
     */
    private function buildBulkAnticipation($bulkAnticipationData)
    {
        $bulkAnticipationData->dateCreated = new \DateTime(
            $bulkAnticipationData->dateCreated
        );
        $bulkAnticipationData->dateUpdated = new \DateTime(
            $bulkAnticipationData->dateUpdated
        );
        $bulkAnticipationData->paymentDate = new \DateTime(
            $bulkAnticipationData->paymentDate
        );

        return new BulkAnticipation(get_object_vars($bulkAnticipationData));
    }
}
