<?php

namespace PagarMe\Sdk\Card;

trait CardBuilder
{
    /**
     * @param $cardData
     * @return Card
     */
    private function buildCard($cardData)
    {
        $cardData->date_created = new \DateTime($cardData->date_created);
        $cardData->date_updated = new \DateTime($cardData->date_updated);

        return new Card($cardData);
    }
}
