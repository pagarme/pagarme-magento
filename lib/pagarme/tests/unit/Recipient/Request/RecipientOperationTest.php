<?php

namespace PagarMe\SdkTests\Recipient;

use PagarMe\Sdk\Recipient\Request\RecipientBalanceOperation;
use PagarMe\Sdk\RequestInterface;

class RecipientBalanceOperationTest extends \PHPUnit_Framework_TestCase
{
    const RECIPIENT_ID = 're_x1y2z3';
    const OPERATION_ID = '123';
    const PATH         = 'recipients/re_x1y2z3/balance/operations/123';


    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $recipientBalanceOperation = new RecipientBalanceOperation(
            $recipientMock,
            self::OPERATION_ID
        );

        $this->assertEquals(self::PATH, $recipientBalanceOperation->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $recipientBalanceOperation = new RecipientBalanceOperation(
            $recipientMock,
            self::OPERATION_ID
        );

        $this->assertEquals(RequestInterface::HTTP_GET, $recipientBalanceOperation->getMethod());
    }

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $recipientBalanceOperation = new RecipientBalanceOperation(
            $recipientMock,
            self::OPERATION_ID
        );

        $this->assertEquals(
            [],
            $recipientBalanceOperation->getPayload()
        );
    }
}
