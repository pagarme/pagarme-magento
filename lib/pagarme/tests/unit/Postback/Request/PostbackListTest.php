<?php

namespace PagarMe\SdkTest\Postback\Request;

use PagarMe\Sdk\Postback\Request\PostbackList;
use PagarMe\Sdk\RequestInterface;

class PostbackListTest extends \PHPUnit_Framework_TestCase
{
    const TRANSACTION_ID = 1234;
    const PATH           = 'transactions/1234/postbacks';


    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $transactionMock = $this->getMockBuilder('PagarMe\Sdk\Transaction\AbstractTransaction')
            ->disableOriginalConstructor()
            ->getMock();

        $transactionMock->method('getId')->willReturn(self::TRANSACTION_ID);

        $postbackList = new PostbackList($transactionMock);

        $this->assertEquals([], $postbackList->getPayload());
        $this->assertEquals(self::PATH, $postbackList->getPath());
        $this->assertEquals(RequestInterface::HTTP_GET, $postbackList->getMethod());
    }
}
