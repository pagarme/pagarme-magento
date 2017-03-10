<?php

class PagarMe_Checkout_Model_CheckoutTest extends PHPUnit_Framework_TestCase
{
    protected $checkoutModel;

    const PAYMENT_AMOUNT_FLOAT   = 13.37;
    const PAYMENT_AMOUNT_INTEGER = 1337;

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
        $boletoTransactionMock = $this->getMockBuilder('PagarMe\\Sdk\\Transaction\\BoletoTransaction')
            ->getMock();

        $boletoTransactionMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn('10');

        $transactionHandlerMock = $this->getMockBuilder('PagarMe\\Sdk\\Transaction\\TransactionHandler')
            ->getMock();

        $transactionHandlerMock->expects($this->once())
            ->method('boletoTransaction')
            ->willReturn($boletoTransactionMock);

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
            'pagarme_checkout_payment_amount'       => self::PAYMENT_AMOUNT_FLOAT
        ];

        $paymentMock = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->getMock();

        $orderMock = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->getMock();

        $orderMock->method('getId')->willReturn(rand(100, 1000));
        $orderMock->method('getIncrementId')->willReturn(rand(100, 1000));

        $paymentMock = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->getMock();

        $paymentMock->method('getOrder')->willReturn($orderMock);

        $infoInstance = Mage::getModel('payment/info');

        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->getMock();

        $infoInstance->setAdditionalInformation(
            [
                'customer' => $customerMock
            ]
        );

        $transactionHandlerMock->expects($this->once())
            ->method('boletoTransaction')
            ->with(
                $this->equalTo(self::PAYMENT_AMOUNT_INTEGER),
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
