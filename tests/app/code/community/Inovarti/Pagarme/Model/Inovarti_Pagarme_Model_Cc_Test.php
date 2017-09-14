<?php

class Inovarti_Pagarme_Model_Cc_Test extends \PHPUnit_Framework_TestCase
{
  	private $creditCardModel = null;

  	public function setUp()
  	{
        $this->creditCardModel = new Inovarti_Pagarme_Model_Cc;
  	}

  	public function installmentNumberProvider()
  	{
    	$config = new Varien_Object();
        $config->setMaxInstallments(12);
        $config->setMinInstallments(1);
        $total = 1000;
        $expected = 12;
        $message = 'Expected customer max installments apply';
        $maxInstallments = [$config, $total, $expected, $message];

        $config = new Varien_Object();
        $config->setMaxInstallments(5);
        $config->setMinInstallments(3);
        $total = 10;
        $expected = 2;
        $message = 'Expected intermediete installments apply';
        $calculatedMinInstallments = [$config, $total, $expected, $message];

        $config = new Varien_Object();
        $config->setMaxInstallments(3);
        $config->setMinInstallments(3);
        $total = 3;
        $expected = 1;
        $message = 'Expected minimum installments apply';
        $minInstallments = [$config, $total, $expected, $message];

        return [$maxInstallments, $calculatedMinInstallments, $minInstallments];
  	}

  	/**
  	 * @dataProvider installmentNumberProvider
  	 **/
  	public function testGetInstallmentNumber($installmentConfig, $total, $expected, $message)
  	{
    	$installmentNumber = $this->creditCardModel->getInstallmentNumber($total, $installmentConfig);
    	$this->assertEquals($expected, $installmentNumber, $message);
  	}
}
