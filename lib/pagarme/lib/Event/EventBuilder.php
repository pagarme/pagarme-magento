<?php

namespace PagarMe\Sdk\Event;

trait EventBuilder
{
    /**
     * @param array $eventData
     */
    public function buildEvent($eventData)
    {
        $eventData->date_created = new \DateTime($eventData->date_created);
        $eventData->date_updated = new \DateTime($eventData->date_updated);
        return new Event($eventData);
    }
}
