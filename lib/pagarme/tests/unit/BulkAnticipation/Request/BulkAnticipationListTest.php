<?php

namespace PagarMe\SdkTest\BankAccount\Request;

use PagarMe\Sdk\BulkAnticipation\Request\BulkAnticipationList;
use PagarMe\Sdk\RequestInterface;

class BulkAnticipationListTest extends \PHPUnit_Framework_TestCase
{
    const PATH         = 'recipients/re_123456/bulk_anticipations';
    const RECIPIENT_ID = 're_123456';

    public function bulkAnticipationListParams()
    {
        return [
            [null, null],
            [1, null],
            [null, 2],
            [3, 4],
        ];
    }

    /**
     * @dataProvider bulkAnticipationListParams
     * @test
     */
    public function mustContentBeCorrect($page, $count)
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $bulkAnticipationList = new BulkAnticipationList(
            $recipientMock,
            $page,
            $count
        );

        $this->assertEquals(
            [
                'page'  => $page,
                'count' => $count
            ],
            $bulkAnticipationList->getPayload()
        );
        $this->assertEquals(self::PATH, $bulkAnticipationList->getPath());
        $this->assertEquals(RequestInterface::HTTP_GET, $bulkAnticipationList->getMethod());
    }
}
