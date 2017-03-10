<?php

class PagarMe_Core_Model_Sdk_AdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function mustReturnInstanceOfPagarMe()
    {
        $sdk = Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk();

        $this->assertInstanceOf('\PagarMe\Sdk\PagarMe', $sdk);

        return $sdk;
    }
}
