<?php

namespace PagarMe\SdkTest\BalanceOperation\Request;

use PagarMe\Sdk\BalanceOperation\Request\BalanceOperationGet;
use PagarMe\Sdk\RequestInterface;

class BalanceOperationGetTest extends \PHPUnit_Framework_TestCase
{
    const PATH                  = 'balance/operations/123';
    const BALANCE_OPERATIONS_ID = '123';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $request = new BalanceOperationGet(self::BALANCE_OPERATIONS_ID);

        $this->assertEquals(RequestInterface::HTTP_GET, $request->getMethod());
        $this->assertEquals(self::PATH, $request->getPath());
        $this->assertEquals([], $request->getPayload());
    }
}
