<?php

namespace PagarMe\SdkTest\BankAccount\Request;

use PagarMe\Sdk\BankAccount\Request\BankAccountList;
use PagarMe\Sdk\RequestInterface;

class BankAccountListTest extends \PHPUnit_Framework_TestCase
{
    const PATH = 'bank_accounts';

    public function bankAccountListParams()
    {
        return [
            [null, null],
            [1, null],
            [null, 2],
            [3, 4],
        ];
    }

    /**
     * @dataProvider bankAccountListParams
     * @test
     */
    public function mustContentBeCorrect($page, $count)
    {
        $request = new BankAccountList(
            $page,
            $count
        );

        $this->assertEquals(RequestInterface::HTTP_GET, $request->getMethod());
        $this->assertEquals(self::PATH, $request->getPath());
        $this->assertEquals(
            [
                'page'  => $page,
                'count' => $count
            ],
            $request->getPayload()
        );
    }
}
