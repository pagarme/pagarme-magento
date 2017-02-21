<?php

class PagarMe_Checkout_Model_CheckoutTest extends PHPUnit_Framework_TestCase
{
    protected $checkoutModel;

    public function setUp()
    {
        $this->checkoutModel = Mage::getModel('pagarme_checkout/checkout');
    }

    /**
     */
    public function mustBeCreateCustomerAtAssignData()
    {
        $data = [
            'pagarme_checkout_customer_name' => 'João',
            'pagarme_checkout_customer_born_at' => null,
            'pagarme_checkout_customer_document_number' => '385.581.581-58',
            'pagarme_checkout_customer_document_type' => 'cpf',
            'pagarme_checkout_customer_phone_ddd' => '11',
            'pagarme_checkout_customer_phone_ddd' => '11',
            'pagarme_checkout_customer_address' => 'joao@joao.com',
            'pagarme_checkout_customer_gender' => null,
        ];

        $infoInstanceMock = $this->getMockBuilder('Mage_Payment_Model_Info')
            ->getMock();

        $infoInstanceMock->expects($this->once())
            ->method('setCustomer')
            ->with(
                $this->isInstanceOf('PagarMe\Sdk\Customer\Customer')
            );

        $this->checkoutModel->setInfoInstance($infoInstanceMock);
        $this->checkoutModel->assignData($data);
    }

    /**
     * @test
     */
    public function mustCreateBoletoTransaction()
    {
        $transactionHandlerMock = $this->getMockBuilder('PagarMe\\Sdk\\Transaction\\TransactionHandler')
            ->getMock();

        $pagarMeMock = $this->getMockBuilder('PagarMe\\Sdk\\PagarMe')
            ->getMock();

        $pagarMeMock->method('transaction')
            ->willReturn($transactionHandlerMock);

        $sdkMock = $this->getMockBuilder('PagarMe_Core_Model_Sdk_Adapter')
            ->getMock();

        $sdkMock->method('getPagarMeSdk')
            ->willReturn($sdkMock);

        $paymentData = [
            'pagarme_checkout_payment_method'       => 'boleto',
            'pagarme_checkout_payment_installments' => 1,
            'pagarme_checkout_payment_amount'       => 10.0
        ];

        $paymentMock = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->getMock();

        $infoInstance = Mage::getModel('payment/info');
        $infoInstance->setCustomer(new \PagarMe\Sdk\Customer\Customer());

        $transactionHandlerMock->expects($this->once())
            ->method('boletoTransaction')
            ->with(
                $this->equalTo($paymentData['pagarme_checkout_payment_amount']),
                $this->isInstanceOf('PagarMe\Sdk\Customer\Customer'),
                $this->anything()
            );

        $this->checkoutModel->setPagarMeSdk($pagarMeMock);
        $this->checkoutModel->setInfoInstance($infoInstance);
        $this->checkoutModel->authorize(
            $paymentMock,
            $paymentData['pagarme_checkout_payment_amount']
        );
    }
}
