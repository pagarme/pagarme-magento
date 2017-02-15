<?php

namespace PagarMe\SdkTests\Recipient;

use PagarMe\Sdk\Recipient\Request\RecipientBalanceOperations;
use PagarMe\Sdk\RequestInterface;

class RecipientBalanceOperationsTest extends \PHPUnit_Framework_TestCase
{
    const RECIPIENT_ID = 're_x1y2z3';
    const PATH         = 'recipients/re_x1y2z3/balance/operations';

    public function recipientBalanceOperationsMock()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        return [
            [$recipientMock, null, null],
            [$recipientMock, 1, 2],
            [$recipientMock, 3, null],
            [$recipientMock, null, 3],
        ];
    }

    /**
     * @dataProvider recipientBalanceOperationsMock
     * @test
     */
    public function mustPathBeCorrect($recipientMock, $page, $count)
    {
        $recipientBalanceOperations = new RecipientBalanceOperations(
            $recipientMock,
            $page,
            $count
        );

        $this->assertEquals(self::PATH, $recipientBalanceOperations->getPath());
    }

    /**
     * @dataProvider recipientBalanceOperationsMock
     * @test
     */
    public function mustMethodBeCorrect($recipientMock, $page, $count)
    {
        $recipientBalanceOperations = new RecipientBalanceOperations(
            $recipientMock,
            $page,
            $count
        );

        $this->assertEquals(RequestInterface::HTTP_GET, $recipientBalanceOperations->getMethod());
    }


    /**
     * @dataProvider recipientBalanceOperationsMock
     * @test
     */
    public function mustPayloadBeCorrect($recipientMock, $page, $count)
    {
        $recipientBalanceOperations = new RecipientBalanceOperations(
            $recipientMock,
            $page,
            $count
        );

        $this->assertEquals(
            [
                'page' => $page,
                'count' => $count
            ],
            $recipientBalanceOperations->getPayload()
        );
    }
}
