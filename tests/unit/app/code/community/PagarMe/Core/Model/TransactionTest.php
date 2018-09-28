<?php

class PagarMeCoreModelTransactionTest extends PHPUnit_Framework_TestCase
{
    private $trxModel;

    public function setUp()
    {
        $this->trxModel = new PagarMe_Core_Model_Transaction();
    }

    /**
     * @test
     */
    public function mustReturnATransactionReferenceKeyHash()
    {
        $refKey = $this->trxModel->getReferenceKey();

        $this->assertInternalType('string', $refKey);

        $this->assertGreaterThanOrEqual(
            PagarMe_Core_Model_Transaction::REFERENCE_KEY_MIN_LENGTH,
            strlen($refKey),
            sprintf(
                'Failed asserting that reference key have %s caracters at least',
                PagarMe_Core_Model_Transaction::REFERENCE_KEY_MIN_LENGTH
            )
        );
    }
}
