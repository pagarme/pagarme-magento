<?php

class PagarMe_Core_Model_Entity_PaymentMethodFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @dataProvider getPaymentMethod
     */
    public function createAValidTransactionObject($paymentMethod)
    {
        $data = [
            'pagarme_checkout_payment_method' => $paymentMethod,
            'pagarme_checkout_token' => 'abc1234',
        ];
        
        $checkoutObject = Mage::getModel('pagarme_checkout/checkout');
        $orderPaymentMock = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->getMock();
        $orderPaymentMock->method('getInfoInstance')
            ->willReturn($checkoutObject);
        $orderPaymentMock->method('getAdditionalInformation')
            ->willReturn($paymentMethod);

        $checkoutObject->setInfoInstance($orderPaymentMock);
        $checkoutObject->assignData($data);

        $paymentFactory = Mage::getModel('pagarme_core/entity_PaymentMethodFactory');
        
        $subject = $paymentFactory->createTransactionObject(
            12345,
            $checkoutObject->getInfoInstance()
        );
        
        $this->assertInstanceOf(
            'PagarMe\Sdk\Transaction\AbstractTransaction',
            $subject
        );
    }

    /**
     * @test
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Unsupported payment method: potato
     */
    public function throwExceptionWhenPaymentMethodIsNotSupported()
    {
        $this->createAValidTransactionObject('potato');
    }

    /**
     * @return array
     */
    public function getPaymentMethod()
    {
        return [
            ['pagarme_checkout_credit_card'],
            ['pagarme_checkout_boleto']
        ];
    }
}
