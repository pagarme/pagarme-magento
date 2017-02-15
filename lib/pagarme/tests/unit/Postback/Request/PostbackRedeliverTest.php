<?php

namespace PagarMe\SdkTest\Postback\Request;

use PagarMe\Sdk\Postback\Request\PostbackRedeliver;
use PagarMe\Sdk\RequestInterface;

class PostbackRedeliverTest extends \PHPUnit_Framework_TestCase
{
    const TRANSACTION_ID = 1234;
    const POSTBACK_ID    = 'po_10000001';
    const PATH           = 'transactions/1234/postbacks/po_10000001/redeliver';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $transactionMock = $this->getMockBuilder('PagarMe\Sdk\Transaction\AbstractTransaction')
            ->disableOriginalConstructor()
            ->getMock();

        $transactionMock->method('getId')->willReturn(self::TRANSACTION_ID);

        $postbackRedeliver = new PostbackRedeliver(
            $transactionMock,
            self::POSTBACK_ID
        );

        $this->assertEquals([], $postbackRedeliver->getPayload());
        $this->assertEquals(self::PATH, $postbackRedeliver->getPath());
        $this->assertEquals(RequestInterface::HTTP_POST, $postbackRedeliver->getMethod());
    }
}
