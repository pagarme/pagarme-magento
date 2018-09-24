<?php

use PagarMe\Sdk\PagarMe;

class PagarMe_CreditCard_Model_InstallmentsTest extends PHPUnit_Framework_TestCase
{
    private function getSdkMock()
    {
        $sdkMock = $this->getMockBuilder('\PagarMe\Sdk\PagarMe')
            ->disableOriginalConstructor()
            ->setMethods([
                'calculation',
                'calculateInstallmentsAmount'
            ])
            ->getMock();

        $sdkMock->expects($this->any())
            ->method('calculation')
            ->will($this->returnSelf());

        return $sdkMock;
    }

    public function testCalculateInstallmentsWithInterestRate() {
        $amount = 100;
        $installments = 2;
        $freeInstallments = 0;
        $interestRate = 10;
        $maxInstallments = 2;

        $sdkMock = $this->getSdkMock();

        $sdkMock->expects($this->any())
            ->method('calculateInstallmentsAmount')
            ->with(
                $this->equalTo($amount),
                $this->equalTo($interestRate),
                $this->equalTo($freeInstallments),
                $this->equalTo($maxInstallments)
            )
            ->willReturn([
                '1' => [
                    'installment_amount' => 55,
                    'total_amount' => 110
                ],
                '2' => [
                    'installment_amount' => 60,
                    'total_amount' => 120
                ]
            ]);

        $installmentsCalc = new PagarMe_CreditCard_Model_Installments(
            $amount,
            $installments,
            $freeInstallments,
            $interestRate,
            $maxInstallments,
            $sdkMock
        );

        $this->assertEquals(120, $installmentsCalc->getTotal());
    }
}
