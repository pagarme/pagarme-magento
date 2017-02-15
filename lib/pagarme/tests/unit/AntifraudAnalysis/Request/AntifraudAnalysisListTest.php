<?php

namespace PagarMe\SdkTest\AntifraudAnalysis\Request;

use PagarMe\Sdk\AntifraudAnalysis\Request\AntifraudAnalysisList;
use PagarMe\Sdk\RequestInterface;

class AntifraudAnalysisListTest extends \PHPUnit_Framework_TestCase
{
    const PATH           = 'transactions/112233/antifraud_analyses';
    const TRANSACTION_ID = 112233;

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

        $antifraudAnalysisList = new AntifraudAnalysisList($transactionMock);

        $this->assertEquals([], $antifraudAnalysisList->getPayload());
        $this->assertEquals(RequestInterface::HTTP_GET, $antifraudAnalysisList->getMethod());
        $this->assertEquals(self::PATH, $antifraudAnalysisList->getPath());
    }
}
