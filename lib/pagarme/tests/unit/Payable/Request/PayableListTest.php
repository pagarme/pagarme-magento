<?php

namespace PagarMe\SdkTest\Payable\Request;

use PagarMe\Sdk\Payable\Request\PayableList;
use PagarMe\Sdk\RequestInterface;

class PayableListTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'payables';

    public function payableListParams()
    {
        return [
            [null, null],
            [1, null],
            [null, 2],
            [3, 4],
        ];
    }

    /**
     * @dataProvider payableListParams
     * @test
     */
    public function mustContentBeCorrect($page, $count)
    {
        $request = new PayableList($page, $count);

        $this->assertEquals(self::PATH, $request->getPath());
        $this->assertEquals(RequestInterface::HTTP_GET, $request->getMethod());
        $this->assertEquals(
            [
                'page'  => $page,
                'count' => $count
            ],
            $request->getPayload()
        );
    }
}
