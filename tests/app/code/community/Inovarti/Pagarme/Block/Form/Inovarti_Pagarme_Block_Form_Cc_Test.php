<?php

class Inovarti_Pagarme_Block_Form_Cc_Test extends \PHPUnit_Framework_TestCase
{
  	private $creditCardFormBlock = null;

  	public function setUp()
  	{
        $this->creditCardFormBlock = $this->mockCardFormBlockMethods(['getPagarmeAPI']);
  	}

    public function mockCardFormBlockMethods($methods)
    {
        return $this->getMockBuilder('Inovarti_Pagarme_Block_Form_Cc')
            ->setMethods($methods)
            ->getMock();
    }

    public function getMockInstallment($amount, $quota)
    {
        $installment = new Varien_Object();
        $installment->setInstallmentAmount($amount);
        $installment->setInstallment($quota);

        return $installment;
    }

    public function assertPreConditions()
    {  
        $this->assertInstanceOf(
          $class = 'Inovarti_Pagarme_Block_Form_Cc',
          $this->creditCardFormBlock,
          "Expects instance of {$class}"
        );

        $this->assertEquals('pagarme_cc', Inovarti_Pagarme_Block_Form_Cc::PAYMENT_METHOD_TYPE);
    }

    public function getInstallmentsCollection($installments)
    {
        $installmentsCollection= new Varien_Object();
        $installmentsCollection->setInstallments($installments);

        return $installmentsCollection;
    }

    public function installmentesProvider()
    {
        $installment1 = $this->getMockInstallment(1000, 1);
        $collection = [$installment1];
        $installments = $this->getInstallmentsCollection($collection);
        $expectOptionText = 'Pay in full - R$10.00';
        $message = 'Pay in full option expected R$10.00';
        $payFullOption = [$installments, $expectOptionText, $message];

        $installment2 = $this->getMockInstallment(500, 2);
        $installment3 = $this->getMockInstallment(333, 3);
        $collection = array_merge($collection, [$installment2, $installment3]);
        $installments = $this->getInstallmentsCollection($collection);
        $expectOptionText = '3x - R$3.33 interest-free';
        $message = '3th installment expected R$3.33';
        $interestFreeOption = [$installments, $expectOptionText, $message];

        $installment4 = $this->getMockInstallment(250, 4);
        $installment5 = $this->getMockInstallment(200, 5);
        $installment6 = $this->getMockInstallment(166, 6);
        $collection = array_merge($collection, [$installment4, $installment5, $installment6]);
        $installments = $this->getInstallmentsCollection($collection);
        $expectOptionText = '6x - R$1.66 monthly interest rate (1.5%)';
        $message = '6th installment expected R$1.66 with interest rate (1.5%)';
        $interestRateOption = [$installments, $expectOptionText, $message];

        return [$payFullOption, $interestFreeOption, $interestRateOption];
    }

    /**
     * @dataProvider installmentesProvider
     */
    public function testGetInstallmentsAvailablesReturnDefaultOptionSuccessfully(
      $installmentsCollection,
      $expectOptionText,
      $message
    ) { 
        $apiMock = $this->getMockBuilder('Inovarti_Pagarme_Model_Api')->getMock();
        $apiMock->method('calculateInstallmentsAmount')->willReturn($installmentsCollection);
        $this->creditCardFormBlock->method('getPagarmeAPI')->willReturn($apiMock);

        $installments = $this->creditCardFormBlock->getInstallmentsAvailables();

        $this->assertContains($expectOptionText, $installments, $message);
    }

    public function discountRulesProvider()
    {
        $creditCardMethod = Inovarti_Pagarme_Block_Form_Cc::PAYMENT_METHOD_TYPE;
        $boletoMethod = 'pagarme_boleto';
        $validade = false;
        $expected = false;

        $message = 'Expects not to give discount for differents payment methods';
        $rulesNotValid = [$creditCardMethod, $validade, $expected, $message];

        $validade = true;
        $message = 'Expects not to give discount for invalid rules';
        $differentsMethods = [$boletoMethod, $validade, $expected, $message];

        $expected = true;
        $message = 'Expects to give discount for the same payment methods and valid rules';
        $discountSuccessfully = [$creditCardMethod, $validade, $expected, $message];

        return [$differentsMethods, $rulesNotValid, $discountSuccessfully];
    }

    /**
     * @dataProvider discountRulesProvider
     */
    public function testHasCreditCardDiscountRules(
      $checkoutPaymentMethod,
      $ruleValidade,
      $expected,
      $message
    ) { 
        $rulesMock = $this->getMockBuilder('Mage_SalesRule_Model_Rule')->getMock();
        $rulesMock->method('validate')->willReturn($ruleValidade);
        $paymentMock = new Varien_Object();
        $paymentMock->setMethod($checkoutPaymentMethod);
        $quoteMock = $this->getMockBuilder('Mage_Sales_Model_Quote')->getMock();
        $quoteMock->method('getPayment')->willReturn($paymentMock);

        $methods = ['getCheckoutQuote', 'getSalesRuleCollection'];
        $this->creditCardFormBlock = $this->mockCardFormBlockMethods($methods);
        $this->creditCardFormBlock->method('getCheckoutQuote')->willReturn($quoteMock);
        $this->creditCardFormBlock->method('getSalesRuleCollection')->willReturn([$rulesMock]);

        $hasDiscountRules = $this->creditCardFormBlock->hasCreditCardDiscountRules();
        $this->assertEquals($expected, $hasDiscountRules, $message);
    }

    public function checkoutTotalAmountProvider()
    { 
        $shipping = 2;
        $baseWithDiscount = 18;
        $baseSubtotal = 10;
        $hasDiscount = true;
        $expected = 20;
        $message = 'Expected to apply discount and billing shipping';
        $discountChipping = [$baseSubtotal, $shipping, $baseWithDiscount, $hasDiscount, $expected, $message];

        $shipping = 0;
        $baseWithDiscount = 18;
        $expected = 18;
        $message = 'Expected to just apply the discount';
        $discountOnly = [$baseSubtotal, $shipping, $baseWithDiscount, $hasDiscount, $expected, $message];

        $shipping = 2;
        $baseSubtotal = 20;
        $hasDiscount = false;
        $expected = 22;
        $message = 'Expected base amount and billing shipping';
        $baseAmountChipping = [$baseSubtotal, $shipping, $baseWithDiscount, $hasDiscount, $expected, $message];

        $shipping = 0;
        $baseSubtotal = 20;
        $hasDiscount = false;
        $expected = 20;
        $message = 'Expected just the base amount';
        $baseAmountOnly = [$baseSubtotal, $shipping, $baseWithDiscount, $hasDiscount, $expected, $message];

        return [$discountChipping, $discountOnly, $baseAmountChipping, $baseAmountOnly];
    }

    /**
     * @dataProvider checkoutTotalAmountProvider
     **/
    public function testgetCheckoutTotalAmount(
      $baseSubtotal,
      $shipping,
      $baseWithDiscount,
      $hasDiscount,
      $expected,
      $message
    ) {
        $shippingAmount = new Varien_Object();
        $shippingAmount->setShippingAmount($shipping);
        $quoteMock = $this->getMockBuilder('Mage_Sales_Model_Quote')
        ->setMethods(['getBaseSubtotal', 'getBaseSubtotalWithDiscount', 'getShippingAddress'])
        ->getMock();
        $quoteMock->method('getBaseSubtotal')->willReturn($baseSubtotal);
        $quoteMock->method('getBaseSubtotalWithDiscount')->willReturn($baseWithDiscount);
        $quoteMock->method('getShippingAddress')->willReturn($shippingAmount);

        $methods = ['getCheckoutQuote', 'hasCreditCardDiscountRules'];
        $this->creditCardFormBlock = $this->mockCardFormBlockMethods($methods);
        $this->creditCardFormBlock->method('getCheckoutQuote')->willReturn($quoteMock);
        $this->creditCardFormBlock->method('hasCreditCardDiscountRules')->willReturn($hasDiscount);

        $amount = $this->creditCardFormBlock->getCheckoutTotalAmount();
        $this->assertEquals($expected, $amount, $message);
    }
}
