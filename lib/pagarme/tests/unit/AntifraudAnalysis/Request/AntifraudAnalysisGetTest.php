<?php

namespace PagarMe\SdkTest\AntifraudAnalysis\Request;

use PagarMe\Sdk\AntifraudAnalysis\Request\AntifraudAnalysisGet;
use PagarMe\Sdk\RequestInterface;

class AntifraudAnalysisGetTest extends \PHPUnit_Framework_TestCase
{
    const PATH                  = 'transactions/112233/antifraud_analyses/123';
    const TRANSACTION_ID        = 112233;
    const ANTIFRAUD_ANALYSES_ID = 123;

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $transactionMock = $this->getMockBuilder(
            'PagarMe\Sdk\Transaction\AbstractTransaction'
        )->disableOriginalConstructor()
        ->getMock();

        $transactionMock->method('getId')
            ->willReturn(self::TRANSACTION_ID);

        $antifraudAnalysisGet = new AntifraudAnalysisGet(
            $transactionMock,
            self::ANTIFRAUD_ANALYSES_ID
        );

        $this->assertEquals([], $antifraudAnalysisGet->getPayload());
        $this->assertEquals(
            RequestInterface::HTTP_GET,
            $antifraudAnalysisGet->getMethod()
        );
        $this->assertEquals(self::PATH, $antifraudAnalysisGet->getPath());
    }
}
