<?php

namespace PagarMe\SdkTest\Transfer\Request;

use PagarMe\Sdk\Transfer\Request\TransferCancel;
use PagarMe\Sdk\RequestInterface;

class TransferCancelTest extends \PHPUnit_Framework_TestCase
{
    const PATH        = 'transfers/123/cancel';
    const TRANSFER_ID = '123';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $transferMock = $this->getMockBuilder('PagarMe\Sdk\Transfer\Transfer')
            ->disableOriginalConstructor()
            ->getMock();
        $transferMock->method('getId')->willReturn(self::TRANSFER_ID);

        $transferCancel = new TransferCancel($transferMock);

        $this->assertEquals([], $transferCancel->getPayload());

        $this->assertEquals(RequestInterface::HTTP_POST, $transferCancel->getMethod());

        $this->assertEquals(self::PATH, $transferCancel->getPath());
    }
}
