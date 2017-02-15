<?php

namespace Pagarme\SdkTests\Plan;

use PagarMe\Sdk\Plan\PlanHandler;

class PlanHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function mustReturnPlan()
    {
        $clientMock =  $this->getMockBuilder('PagarMe\Sdk\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('send')
            ->willReturn(json_decode('{"object":"plan","id":70581,"amount":1337,"days":30,"name":"Plan Teste","trial_days":15,"date_created":"2016-10-31T19:06:11.258Z","payment_methods":["boleto","credit_card"],"charges":13,"installments":26}'));

        $handler = new PlanHandler($clientMock);

        $this->assertInstanceOf(
            'Pagarme\Sdk\Plan\Plan',
            $handler->create(
                1337,
                15,
                'Plano teste',
                10,
                null,
                'Silver',
                13,
                26
            )
        );
    }
}
