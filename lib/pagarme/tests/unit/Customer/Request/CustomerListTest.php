<?php

namespace PagarMe\SdkTest\Customer\Request;

use PagarMe\Sdk\Customer\Request\CustomerList;
use PagarMe\Sdk\RequestInterface;

class CustomerListTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'customers';
    const PAGE   = 7;
    const COUNT  = 42;

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $customerList = new CustomerList(self::PAGE, self::COUNT);

        $this->assertEquals(
            [
                'page'  => self::PAGE,
                'count' => self::COUNT
            ],
            $customerList->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $customerList = new CustomerList(1, 10);
        $this->assertEquals(self::PATH, $customerList->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $customerList = new CustomerList(1, 10);
        $this->assertEquals(RequestInterface::HTTP_GET, $customerList->getMethod());
    }
}
