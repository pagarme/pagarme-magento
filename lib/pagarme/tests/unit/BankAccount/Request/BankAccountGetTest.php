<?php

namespace PagarMe\SdkTest\BankAccount\Request;

use PagarMe\Sdk\BankAccount\Request\BankAccountGet;
use PagarMe\Sdk\RequestInterface;

class BankAccountGetTest extends \PHPUnit_Framework_TestCase
{
    const PATH            = 'bank_accounts/1337';
    const BANK_ACCOUNT_ID = '1337';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $request = new BankAccountGet(self::BANK_ACCOUNT_ID);

        $this->assertEquals(RequestInterface::HTTP_GET, $request->getMethod());
        $this->assertEquals(self::PATH, $request->getPath());
        $this->assertEquals(
            [],
            $request->getPayload()
        );
    }
}
