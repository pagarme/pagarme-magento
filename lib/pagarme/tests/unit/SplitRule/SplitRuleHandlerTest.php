<?php

namespace PagarMe\SdkTest\SplitRule;

use PagarMe\Sdk\SplitRule\SplitRuleHandler;

class SplitRuleHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function splitData()
    {
        return [
            [100, 123, true, false],
            [666, 321, false, false],
            [23, 233, null, false],
            [42, 800, null, true],
        ];
    }

    /**
     * @dataProvider splitData
     * @test
     */
    public function mustReturnSplitRuleWithMonetaryValue(
        $value,
        $recipientId,
        $liable,
        $chargeProcessingFee
    ) {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn($recipientId);

        $splitRuleHandler = new SplitRuleHandler();
        $rule = $splitRuleHandler->monetaryRule(
            $value,
            $recipientMock,
            $liable,
            $chargeProcessingFee
        );

        $this->assertEquals($value, $rule->getAmount());
        $this->assertNull($rule->getPercentage());
        $this->assertEquals($liable, $rule->getLiable());
        $this->assertEquals($chargeProcessingFee, $rule->getChargeProcessingFee());
        $this->assertEquals($recipientId, $rule->getRecipient()->getId());
    }

    /**
     * @dataProvider splitData
     * @test
     */
    public function mustReturnSplitRuleWithPercentageValue(
        $value,
        $recipientId,
        $liable,
        $chargeProcessingFee
    ) {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();
        $recipientMock->method('getId')->willReturn($recipientId);

        $splitRuleHandler = new SplitRuleHandler();
        $rule = $splitRuleHandler->percentageRule(
            $value,
            $recipientMock,
            $liable,
            $chargeProcessingFee
        );

        $this->assertEquals($value, $rule->getPercentage());
        $this->assertNull($rule->getAmount());
        $this->assertEquals($liable, $rule->getLiable());
        $this->assertEquals($chargeProcessingFee, $rule->getChargeProcessingFee());
        $this->assertEquals($recipientId, $rule->getRecipient()->getId());
    }
}
