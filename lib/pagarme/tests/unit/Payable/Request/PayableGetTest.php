<?php

namespace PagarMe\SdkTest\Payable\Request;

use PagarMe\Sdk\Payable\Request\PayableGet;
use PagarMe\Sdk\RequestInterface;

class PayableGetTest extends \PHPUnit_Framework_TestCase
{
    const PAYABLE_ID = 123456;
    const PATH       = 'payables/123456';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $payableGet = new PayableGet(self::PAYABLE_ID);

        $this->assertEquals([], $payableGet->getPayload());
        $this->assertEquals(RequestInterface::HTTP_GET, $payableGet->getMethod());
        $this->assertEquals(self::PATH, $payableGet->getPath());
    }
}
