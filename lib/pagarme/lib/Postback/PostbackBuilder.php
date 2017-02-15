<?php

namespace PagarMe\Sdk\Postback;

trait PostbackBuilder
{
    use PayloadBuilder, PostbackDeliveryBuilder;

    /**
     * @param stdClass $postbackData
     * @return Postback
     */
    private function buildPostback($postbackData)
    {
        $postbackDeliveries = [];

        foreach ($postbackData->deliveries as $postbackDeliveryData) {
            $postbackDeliveries[] =$this->buildPostbackDelivery(
                $postbackDeliveryData
            );
        }

        $postbackData->date_created = new \DateTime(
            $postbackData->date_created
        );
        $postbackData->date_updated = new \DateTime(
            $postbackData->date_updated
        );
        $postbackData->deliveries = $postbackDeliveries;

        $postbackData->payload = $this->buildPayload(
            $postbackData->payload
        );

        return new Postback(get_object_vars($postbackData));
    }

    /**
     * @param array $postbackDeliveryData
     * @return Delivery
     */
    private function buildPostbackDelivery($postbackDeliveryData)
    {
        $postbackDeliveryData->date_created = new \DateTime(
            $postbackDeliveryData->date_created
        );
        $postbackDeliveryData->date_updated = new \DateTime(
            $postbackDeliveryData->date_updated
        );

        return new Delivery($postbackDeliveryData);
    }

    /**
     * @param array $payloadData
     * @return Payload
     */
    private function buildPayload($payloadData)
    {
        parse_str($payloadData, $payload);

        $payload['transaction'] = $this->buildTransaction(
            (object) $payload['transaction']
        );

        return new Payload($payload);
    }
}
