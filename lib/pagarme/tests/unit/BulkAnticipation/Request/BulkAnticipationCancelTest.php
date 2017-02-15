<?php

namespace PagarMe\SdkTest\BankAccount\Request;

use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationCancel;
use PagarMe\Sdk\RequestInterface;

class BulkAnticipationCancelTest extends \PHPUnit_Framework_TestCase
{
    const PATH         = 'recipients/re_123456/bulk_anticipations/ba_123456/cancel';
    const RECIPIENT_ID = 're_123456';

    const ANTICIPATION_ID = 'ba_123456';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $bulkAnticipationMock = $this->getMockBuilder('PagarMe\Sdk\BulkAnticipation\BulkAnticipation')
            ->disableOriginalConstructor()
            ->getMock();
        $bulkAnticipationMock->method('getId')->willReturn(self::ANTICIPATION_ID);

        $bulkAnticipationCancel = new BulkAnticipationCancel(
            $recipientMock,
            $bulkAnticipationMock
        );

        $this->assertEquals([], $bulkAnticipationCancel->getPayload());
        $this->assertEquals(self::PATH, $bulkAnticipationCancel->getPath());
        $this->assertEquals(RequestInterface::HTTP_POST, $bulkAnticipationCancel->getMethod());
    }
}
