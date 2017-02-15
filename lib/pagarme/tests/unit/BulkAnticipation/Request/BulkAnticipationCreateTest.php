<?php

namespace PagarMe\SdkTest\BankAccount\Request;

use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationCreate;
use PagarMe\Sdk\RequestInterface;

class BulkAnticipationCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH         = 'recipients/re_123456/bulk_anticipations';
    const RECIPIENT_ID = 're_123456';

    public function bulkAnticipationProvider()
    {
        return [
            [new \DateTime('2017-12-13'), 'end', 80486, true],
            [new \DateTime('2018-11-11'), 'start', 10001, false],
            [new \DateTime('2019-01-23'), 'end', 123456, false],
            [new \DateTime('2020-03-30'), 'end', 515151, true]
        ];
    }

    /**
     * @dataProvider bulkAnticipationProvider
     * @test
     */
    public function mustContentBeCorrect(
        $paymentDate,
        $timeframe,
        $requestedAmount,
        $building
    ) {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $bulkAnticipationCreate = new BulkAnticipationCreate(
            $recipientMock,
            $paymentDate,
            $timeframe,
            $requestedAmount,
            $building
        );

        $this->assertEquals(
            [
                'payment_date'     => substr($paymentDate->format('Uu'), 0, 13),
                'timeframe'        => $timeframe,
                'requested_amount' => $requestedAmount,
                'building'         => $building
            ],
            $bulkAnticipationCreate->getPayload()
        );
        $this->assertEquals(self::PATH, $bulkAnticipationCreate->getPath());
        $this->assertEquals(RequestInterface::HTTP_POST, $bulkAnticipationCreate->getMethod());
    }
}
