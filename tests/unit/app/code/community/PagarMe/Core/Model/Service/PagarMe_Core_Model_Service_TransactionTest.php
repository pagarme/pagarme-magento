<?php

class PagarMe_Core_Model_Service_TransactionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PagarMe_Core_Model_Service_Transaction
     */
    private $model;

    /**
     * @void
     */
    public function setUp()
    {
        $this->model = new PagarMe_Core_Model_Service_Transaction();

        $sdkMock = $this
            ->getMockBuilder('\PagarMe\Sdk\PagarMe')
            ->disableOriginalConstructor()
            ->setMethods(['get', 'transaction'])
            ->getMock();

        $sdkMock
            ->expects($this->any())
            ->method('get')
            ->willReturn(
                new PagarMe\Sdk\Transaction\CreditCardTransaction([])
            );

        $sdkMock
            ->expects($this->any())
            ->method('transaction')
            ->will($this->returnSelf());

        $this->model->setPagarMeSdk($sdkMock);
    }

    /**
     * @test
     */
    public function mustReturnATransactionObject()
    {
        $transactionId = 1;
        $transaction = $this->model->getTransactionById($transactionId);

        $this->assertInstanceOf(
            'PagarMe\Sdk\Transaction\AbstractTransaction',
            $transaction
        );
    }
}
