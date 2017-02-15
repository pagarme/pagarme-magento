<?php

namespace PagarMe\SdkTest\Request;

use PagarMe\Sdk\Plan\Request\PlanGet;
use PagarMe\Sdk\RequestInterface;

class PlanGetTest extends \PHPUnit_Framework_TestCase
{
    const PATH    = 'plans/123';
    const PLAN_ID = '123';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $request = new PlanGet(self::PLAN_ID);

        $this->assertEquals(self::PATH, $request->getPath());
        $this->assertEquals(RequestInterface::HTTP_GET, $request->getMethod());
        $this->assertEquals(
            [],
            $request->getPayload()
        );
    }
}
