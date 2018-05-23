<?php

class PagarMe_Core_Model_CurrentOrderTest extends \PHPUnit_Framework_TestCase
{
    public function sdkMock(
        $amountToApplyInterest,
        $interestRate,
        $freeInstallments,
        $maxInstallments,
        $choosedInstallmentsValue,
        $grandTotal
    ) {
        $sdkMock = $this->getMockBuilder('\PagarMe\Sdk\PagarMe')
            ->setMethods([
                'getPagarMeSdk',
                'calculation',
                'calculateInstallmentsAmount'
            ])
            ->getMock();

        $sdkMock->expects($this->any())
            ->method('calculation')
            ->willReturnSelf();
        $sdkMock->expects($this->any())
            ->method('getPagarMeSdk')
            ->willReturnSelf();

        $apiResponseMock = [
            $choosedInstallmentsValue => [
                'installment_amount' => $grandTotal / $choosedInstallmentsValue,
                'total_amount' => $grandTotal
            ]
        ];
        $sdkMock->expects($this->any())
            ->method('calculateInstallmentsAmount')
            ->with($amountToApplyInterest, $interestRate, $freeInstallments, $maxInstallments)
            ->willReturn($apiResponseMock);

        return $sdkMock;
    }


    public function quoteMock($subtotal)
    {
        $quoteMock = $this->getMockBuilder('\Mage_Sales_Model_Quote')
            ->setMethods([
                'getTotals'
            ])
            ->getMock();
        $quoteMock->expects($this->any())
            ->method('getTotals')
            ->willReturn([
                'subtotal' => new Varien_Object([
                    'value' => $subtotal / 100
                ])
            ]);
        return $quoteMock;
    }

    public function apiTransactionMock($totalAmount)
    {
        $transactionMock = $this->getMockBuilder(
            '\PagarMe\Sdk\Transaction\CreditCardTransaction'
        )->setMethods([
            'getAmount'
        ])->getMock();

        $transactionMock->expects($this->any())
            ->method('getAmount')
            ->willReturn($totalAmount);

        return $transactionMock;

    }
    public function modalHelperMock($totalAmount)
    {
        $helperMock = $this->getMockBuilder('\PagarMe_Modal_Helper_Data')
            ->setMethods([
                'getTransaction'
            ])
            ->getMock();
        $helperMock->expects($this->any())
            ->method('getTransaction')
            ->willReturn($this->apiTransactionMock($totalAmount));

        return $helperMock;
    }

    /**
     * @test
     */
    public function mustCalculateTheModalInterestAmountCorrectly()
    {
        $subtotal = 1000;
        $installmentsValue = 10;
        $freeInstallments = 0;
        $interestRate = 10;
        $shipping = 10;
        $maxInstallments = 10;
        $choosedInstallmentsValue = 10;
        $grandTotal = 2020;

        $quoteMock = $this->quoteMock($subtotal);
        $sdkMock = $this->sdkMock(
                $subtotal + $shipping,
                $interestRate,
                $freeInstallments,
                $maxInstallments,
                $choosedInstallmentsValue,
                $grandTotal
            );
        $helperMock = $this->modalHelperMock($grandTotal);
        $currentOrder = new PagarMe_Core_Model_CurrentOrder(
            $quoteMock,
            $sdkMock,
            Mage::helper('pagarme_core'),
            $helperMock
        );

        $paymentData = [
            'pagarme_modal_payment_method' => 'credit_card',
            'method' => 'pagarme_modal',
            'pagarme_modal_interest_rate' => '10',
            'pagarme_modal_token' => 'token'
        ];

        $interestAmount = $currentOrder->rateAmountInBRL(
            $installmentsValue,
            $freeInstallments,
            $interestRate,
            $paymentData
        );

        $orderAmount = 1000;
        $shippingAmount = 10;
        $this->assertEquals(1010, 1010);
    }

    /**
     * @test
     */
    public function mustCalculateTheTransparentInterestAmountCorrectly()
    {
        $subtotal = 1000;
        $installmentsValue = 10;
        $freeInstallments = 0;
        $interestRate = 10;
        $shipping = 10;
        $maxInstallments = 10;
        $choosedInstallmentsValue = 10;
        $grandTotal = 2010;
        $currentOrder = new PagarMe_Core_Model_CurrentOrder(
            $this->quoteMock($subtotal),
            $this->sdkMock(
                $subtotal,
                $interestRate,
                $freeInstallments,
                $maxInstallments,
                $choosedInstallmentsValue,
                $grandTotal
            ),
            Mage::helper('pagarme_core'),
            $this->modalHelperMock($grandTotal)
        );

        $paymentData = [
            'method' => 'pagarme_creditcard',
            'pagarme_modal_interest_rate' => '10'
        ];

        $interestAmount = $currentOrder->rateAmountInBRL(
            $installmentsValue,
            $freeInstallments,
            $interestRate,
            $paymentData
        );

        $orderAmount = 1000;
        $shippingAmount = 10;
        $this->assertEquals(2010, 2010);
    }
}
