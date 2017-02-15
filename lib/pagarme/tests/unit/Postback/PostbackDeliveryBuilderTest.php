<?php

namespace PagarMe\Sdk\Postback;

class PostbackDeliveryBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Postback\PostbackDeliveryBuilder;

    /**
     * @test
     */
    public function mustCreatePayloadDeliveryCorrectly()
    {
        $response = '{"object":"postback_delivery","status":"processing","status_reason":null,"status_code":null,"response_time":null,"response_headers":null,"response_body":null,"date_created":"2016-12-30T17:18:06.291Z","date_updated":"2016-12-30T17:18:06.291Z","id":"pd_cixc2c1qr02okj97325zajshl"}';

        $payloadDelivery = $this->buildPostbackDelivery(json_decode($response));

        $this->assertInstanceOf(
            'PagarMe\Sdk\Postback\Delivery',
            $payloadDelivery
        );
        $this->assertInstanceOf(
            '\DateTime',
            $payloadDelivery->getDateCreated()
        );
        $this->assertInstanceOf(
            '\DateTime',
            $payloadDelivery->getDateUpdated()
        );
    }
}
