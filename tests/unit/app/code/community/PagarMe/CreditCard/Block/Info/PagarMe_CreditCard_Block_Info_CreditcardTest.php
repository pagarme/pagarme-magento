<?php

class PagarMe_CreditCard_Block_Info_CreditcardTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function mustReturnEmptyStringWhenTheresNoTransaction()
    {
        $blockInfo = $this->getMockBuilder
        (
            'PagarMe_CreditCard_Block_Info_Creditcard'
        )->disableOriginalConstructor()->getMock();

        $this->assertEmpty($blockInfo->renderView());
    }
}
