<?php

class PagarMe_Core_Model_PostbackTest extends \PHPUnit_Framework_TestCase
{
    public function validPostbackDataProvider()
    {
        return [
            [
                true,
                true,
                PagarMe_Core_Model_Postback::POSTBACK_STATUS_PAID
            ],
            [
                true,
                false,
                PagarMe_Core_Model_Postback::POSTBACK_STATUS_REFUNDED
            ]
        ];
    }

    public function invalidPostbackDataProvider()
    {
        return [
            [
                false,
                true,
                'any'
            ],
            [
                false,
                false,
                PagarMe_Core_Model_Postback::POSTBACK_STATUS_PAID
            ]
        ];
    }

    public function postbackDataProvider()
    {
        return array_merge(
            $this->validPostbackDataProvider(),
            $this->invalidPostbackDataProvider()
        );
    }

    /**
     * @test
     * @dataProvider postbackDataProvider
     */
    public function mustProceedWithPostback(
        $expectedValue,
        $orderCanInvoice,
        $currentStatus
    ) {
        $postback = Mage::getModel('pagarme_core/postback');

        $orderMock = $this->getMockBuilder('Mage_Sales_Model_Order')
            ->getMock();

        $orderMock->method('canInvoice')->willReturn($orderCanInvoice);

        $this->assertEquals(
            $expectedValue,
            $postback->canProceedWithPostback($orderMock, $currentStatus)
        );
    }

    /**
     * @test
     */
    public function mustSetOrderAsPaid()
    {
        $postback = Mage::getModel('pagarme_core/postback');

        $resourceMock = $this->getMockBuilder('Mage_Core_Model_Resource_Resource')
            ->getMock();

        $invoiceMock = $this->getMockBuilder('Mage_Sales_Model_Order_Invoice')
            ->getMock();

        $invoiceMock->method('getResource')
            ->willReturn($resourceMock);

        $invoiceMock->expects($this->once())
            ->method('register')
            ->willReturn($invoiceMock);

        $invoiceMock->expects($this->once())
            ->method('pay');

        $invoiceMock->expects($this->once())
            ->method('save');

        $invoiceServiceMock = $this->getMockBuilder('PagarMe_Core_Model_Service_Invoice')
            ->getMock();

        $postback->setInvoiceService($invoiceServiceMock);

        $invoiceServiceMock->expects($this->once())
            ->method('createInvoiceFromOrder')
            ->willReturn($invoiceMock);

        $transactionId = 120;

        $orderMock = $this->getMockBuilder('Mage_Sales_Model_Order')
            ->getMock();

        $orderMock->method('getResource')
            ->willReturn($resourceMock);

        $orderMock->expects($this->once())
            ->method('setState')
            ->with(
                Mage_Sales_Model_Order::STATE_PROCESSING,
                true,
                'pago'
            );

        $orderMock->expects($this->once())
            ->method('save');

        $postback->setOrderAsPaid($orderMock);
    }

    /**
     * @test
     * @expectedException \Exception
     * @dataProvicer invalidPostbackDataProvider
     */
    public function mustThrowExceptionWhenCantProceedWithPostback(
        $expectedValue,
        $orderCanInvoice,
        $currentStatus
    ) {
        $orderMock = $this->getMockBuilder('Mage_Sales_Model_Order')
            ->getMock();

        $orderMock->method('canInvoice')
            ->willReturn($orderCanInvoice);

        $orderServiceMock = $this->getMockBuilder('PagarMe_Core_Model_Service_Order')
            ->getMock();

        $transactionId = rand(0, 9999);

        $orderServiceMock->expects($this->once())
            ->method('getOrderByTransactionId')
            ->with($transactionId)
            ->willReturn($orderMock);

        $postback = Mage::getModel('pagarme_core/postback');
        $postback->setOrderService($orderServiceMock);
        $postback->processPostback($transactionId, $currentStatus);
    }
}
