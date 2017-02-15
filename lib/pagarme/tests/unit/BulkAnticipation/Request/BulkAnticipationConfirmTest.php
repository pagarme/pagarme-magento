<?php

namespace PagarMe\SdkTest\BankAccount\Request;

use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationConfirm;
use PagarMe\Sdk\RequestInterface;

class BulkAnticipationConfirmTest extends \PHPUnit_Framework_TestCase
{
    const PATH         = 'recipients/re_123456/bulk_anticipations/ba_123456/confirm';
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

        $bulkAnticipationConfirm = new BulkAnticipationConfirm(
            $recipientMock,
            $bulkAnticipationMock
        );

        $this->assertEquals([], $bulkAnticipationConfirm->getPayload());
        $this->assertEquals(self::PATH, $bulkAnticipationConfirm->getPath());
        $this->assertEquals(RequestInterface::HTTP_POST, $bulkAnticipationConfirm->getMethod());
    }
}
