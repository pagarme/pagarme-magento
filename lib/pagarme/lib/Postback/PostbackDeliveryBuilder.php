<?php

namespace PagarMe\Sdk\Postback;

trait PostbackDeliveryBuilder
{
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
}
