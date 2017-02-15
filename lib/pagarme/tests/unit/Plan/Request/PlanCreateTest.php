<?php

namespace PagarMe\SdkTest\Request;

use PagarMe\Sdk\Plan\Request\PlanCreate;
use PagarMe\Sdk\RequestInterface;

class PlanCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH = 'plans';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $request = new PlanCreate(
            1337,
            15,
            "Plano teste",
            10,
            null,
            13,
            26
        );

        $this->assertEquals(self::PATH, $request->getPath());
        $this->assertEquals(RequestInterface::HTTP_POST, $request->getMethod());
        $this->assertEquals(
            [
                'amount'          => 1337,
                'days'            => 15,
                'name'            => "Plano teste",
                'trial_days'      => 10,
                'payment_methods' => null,
                'charges'         => 13,
                'installments'    => 26
            ],
            $request->getPayload()
        );
    }
}
