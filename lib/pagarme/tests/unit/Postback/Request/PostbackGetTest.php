<?php

namespace PagarMe\SdkTest\Postback\Request;

use PagarMe\Sdk\Postback\Request\PostbackGet;
use PagarMe\Sdk\RequestInterface;

class PostbackGetTest extends \PHPUnit_Framework_TestCase
{
    const TRANSACTION_ID = 1234;
    const POSTBACK_ID    = 'po_10000001';
    const PATH           = 'transactions/1234/postbacks/po_10000001';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $transactionMock = $this->getMockBuilder('PagarMe\Sdk\Transaction\AbstractTransaction')
            ->disableOriginalConstructor()
            ->getMock();

        $transactionMock->method('getId')->willReturn(self::TRANSACTION_ID);

        $postbackGet = new PostbackGet($transactionMock, self::POSTBACK_ID);

        $this->assertEquals([], $postbackGet->getPayload());
        $this->assertEquals(self::PATH, $postbackGet->getPath());
        $this->assertEquals(RequestInterface::HTTP_GET, $postbackGet->getMethod());
    }
}
