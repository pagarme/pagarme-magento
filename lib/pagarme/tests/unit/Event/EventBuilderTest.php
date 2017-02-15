<?php

namespace PagarMe\SdkTest\Event;

class EventBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Event\EventBuilder;

    /**
     * @test
     */
    public function mustCreateEventCorrectly()
    {
        $payload = '{"object":"event","id":"ev_cixi05vmw04eohx6duqzsgggt","name":"transaction_status_changed","model":"transaction","model_id":"1030997","payload":{"old_status":"processing","desired_status":"paid","current_status":"paid"},"date_created":"2017-01-03T21:03:56.265Z","date_updated":"2017-01-03T21:03:56.265Z"}';

        $event = $this->buildEvent(json_decode($payload));

        $this->assertInstanceOf('PagarMe\Sdk\Event\Event', $event);
        $this->assertInstanceOf('\DateTime', $event->getDateCreated());
        $this->assertInstanceOf('\DateTime', $event->getDateUpdated());
    }
}
