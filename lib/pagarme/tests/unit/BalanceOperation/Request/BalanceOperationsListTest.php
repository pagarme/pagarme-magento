<?php

namespace PagarMe\SdkTest\BalanceOperation\Request;

use PagarMe\Sdk\BalanceOperation\Request\BalanceOperationList;
use PagarMe\Sdk\RequestInterface;

class BalanceOperationListTest extends \PHPUnit_Framework_TestCase
{
    const PATH = 'balance/operations';

    public function balanceOperationListParams()
    {
        return [
            [null, null, null],
            [1, null, null],
            [null, 2, 'waiting_funds'],
            [3, 4, 'avaliable'],
        ];
    }

    /**
     * @dataProvider balanceOperationListParams
     * @test
     */
    public function mustContentBeCorrect($page, $count, $status)
    {
        $request = new BalanceOperationList(
            $page,
            $count,
            $status
        );

        $this->assertEquals(RequestInterface::HTTP_GET, $request->getMethod());
        $this->assertEquals(self::PATH, $request->getPath());
        $this->assertEquals(
            [
                'page'   => $page,
                'count'  => $count,
                'status' => $status
            ],
            $request->getPayload()
        );
    }
}
