<?php

class PagarMe_Checkout_Block_SuccessTest extends PHPUnit_Framework_TestCase
{
    private $successBlock;

    public function setUp()
    {
        $this->successBlock = new PagarMe_Checkout_Block_Success();
    }

    public function paymentMethodsData()
    {
        return [
            [
                PagarMe_Checkout_Model_Checkout::PAGARME_CHECKOUT_BOLETO,
                true
            ],
            [
                'qualquer_outro_metodo',
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider paymentMethodsData
     */
    public function mustVerifyIfIsBoletoTransactionOrNot($paymentMethod, $expect)
    {
        $additionalInfo = [
            'pagarme_payment_method' => $paymentMethod
        ];

        $paymentMock = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->getMock();

        $paymentMock->expects($this->once())
            ->method('getAdditionalInformation')
            ->willReturn($additionalInfo);

        $orderMock = $this->getMockBuilder('Mage_Sales_Model_Order')
            ->getMock();

        $orderMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($paymentMock);

        $this->successBlock->setOrder($orderMock);
        $this->assertEquals($expect, $this->successBlock->isBoletoPayment());
    }

    /**
     * @test
     */
    public function mustReturnBoletoUrl()
    {
        $boletoUrl = 'https://pagar.me/boleto';

        $additionalInfo = [
            'pagarme_payment_method' => $paymentMethod,
            'pagarme_boleto_url'     => $boletoUrl
        ];

        $paymentMock = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->getMock();

        $paymentMock->expects($this->once())
            ->method('getAdditionalInformation')
            ->willReturn($additionalInfo);

        $orderMock = $this->getMockBuilder('Mage_Sales_Model_Order')
            ->getMock();

        $orderMock->expects($this->once())
            ->method('getPayment')
            ->willReturn($paymentMock);

        $this->successBlock->setOrder($orderMock);
        $this->assertEquals($boletoUrl, $this->successBlock->getBoletoUrl());
    }
}
