<?php

class PagarMe_Checkout_Model_CheckoutTest extends PHPUnit_Framework_TestCase
{
    protected $checkoutModel;

    public function setUp()
    {
        $this->checkoutModel = Mage::getModel('pagarme_checkout/checkout');
    }

    /**
     * @test
     */
    public function mustAuthorizeTransaction()
    {
        $installments = 1;
        $cardhash     = 'skdjafçsldjfasçlkdfjasldfjsdlkaf';

        $this->checkoutModel
            ->assignData(
                [
                    'installments' => $installments,
                    'card_hash'    => $cardhash,
                ]
            );

        $payment = $this->getMockBuilder('Varien_Object')
            ->getMock();

        $payment->method('getAmount')
            ->willReturn(10.00);

        $this->checkoutModel->authorize($payment);
    }
}
