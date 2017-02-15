<?php

namespace PagarMe\SdkTest\BankAccount\Request;

use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationLimits;
use PagarMe\Sdk\RequestInterface;

class BulkAnticipationLimitsTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\MicrosecondsFormatter;

    const PATH         = 'recipients/re_123456/bulk_anticipations/limits';
    const RECIPIENT_ID = 're_123456';

    public function bulkAnticipationProvider()
    {
        return [
            [new \DateTime('2017-12-13'), 'end'],
            [new \DateTime('2018-11-11'), 'start'],
            [new \DateTime('2019-01-23'), 'end'],
            [new \DateTime('2020-03-30'), 'end']
        ];
    }

    /**
     * @dataProvider bulkAnticipationProvider
     * @test
     */
    public function mustContentBeCorrect($paymentDate, $timeframe)
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $bulkAnticipationLimits = new BulkAnticipationLimits(
            $recipientMock,
            $paymentDate,
            $timeframe
        );

        $this->assertEquals(
            [
                'payment_date'     => $this->getDateInMicroseconds($paymentDate),
                'timeframe'        => $timeframe
            ],
            $bulkAnticipationLimits->getPayload()
        );
        $this->assertEquals(self::PATH, $bulkAnticipationLimits->getPath());
        $this->assertEquals(RequestInterface::HTTP_GET, $bulkAnticipationLimits->getMethod());
    }
}
