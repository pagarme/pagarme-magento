<?php

namespace PagarMe\SdkTest\AntifraudAnalysis;

class AntifraudAnalysisBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\AntifraudAnalysis\AntifraudAnalysisBuilder;

    /**
     * @test
     */
    public function mustCreateAntifraudAnalysisCorrectly()
    {
        $payload = '{"object":"antifraud_analysis","name":"development","score":null,"cost":0,"status":"failed","date_created":"2017-01-03T21:03:55.922Z","date_updated":"2017-01-03T21:03:55.945Z","id":155174}';

        $antifraudAnalysis = $this->buildAntifraudAnalysis(
            json_decode($payload)
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\AntifraudAnalysis\AntifraudAnalysis',
            $antifraudAnalysis
        );
        $this->assertInstanceOf(
            '\DateTime',
            $antifraudAnalysis->getDateCreated()
        );
        $this->assertInstanceOf(
            '\DateTime',
            $antifraudAnalysis->getDateUpdated()
        );
    }
}
