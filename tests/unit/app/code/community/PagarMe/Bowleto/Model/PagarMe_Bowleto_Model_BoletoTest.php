<?php
/**
 * @author LeonamDias <leonam.pd@gmail.com>
 * @package PHP
 */

use PagarMe\Sdk\Card\Card;
use PagarMe\Sdk\Transaction\AbstractTransaction;
use PagarMe\Sdk\Transaction\BoletoTransaction;
use PagarMe_Bowleto_Model_Boleto as ModelBoleto;

class PagarMeboletoModelBoletoTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function mustReturnFalseIfCheckoutTransparentIsInactive()
    {
        $boletoModel = $this
            ->getMockBuilder('PagarMe_Bowleto_Model_Boleto')
            ->setMethods([
                'isTransparentCheckoutActive',
                'getActiveTransparentPaymentMethod'
            ])
            ->getMock();

        $boletoModel
            ->expects($this->any())
            ->method('isTransparentCheckoutActive')
            ->willReturn(false);

        $this->assertFalse(
            $boletoModel->isAvailable(),
            'Transparent checkout is inactive. Boleto should be unavailable'
        );
    }

    /**
     * @test
     */
    public function mustReturnTrueIfBoletoIsEnabled()
    {
        $boletoModel = $this
            ->getMockBuilder('PagarMe_Bowleto_Model_Boleto')
            ->setMethods([
                'isTransparentCheckoutActive',
                'getActiveTransparentPaymentMethod'
            ])
            ->getMock();

        $boletoModel
            ->expects($this->any())
            ->method('isTransparentCheckoutActive')
            ->willReturn(true);

        $boletoModel
            ->expects($this->any())
            ->method('getActiveTransparentPaymentMethod')
            ->willReturn('pagarme_bowleto');

            $this->assertTrue($boletoModel->isAvailable());
    }

    /**
     * @test
     */
    public function mustReturnFalseIfBoletoIsDisabled()
    {
        $boletoModel = $this
            ->getMockBuilder('PagarMe_Bowleto_Model_Boleto')
            ->setMethods([
                'isTransparentCheckoutActive',
                'getActiveTransparentPaymentMethod'
            ])
            ->getMock();

        $boletoModel
            ->expects($this->any())
            ->method('isTransparentCheckoutActive')
            ->willReturn(true);

        $boletoModel
            ->expects($this->any())
            ->method('getActiveTransparentPaymentMethod')
            ->willReturn('pagarme_creditcard');

        $this->assertFalse($boletoModel->isAvailable());
    }

    /**
     * @test
     */
    public function mustReturnTrueIfCreditCardAndBoletoIsEnabled()
    {
        $boletoModel = $this
            ->getMockBuilder('PagarMe_Bowleto_Model_Boleto')
            ->setMethods([
                'isTransparentCheckoutActive',
                'getActiveTransparentPaymentMethod'
            ])
            ->getMock();

        $boletoModel
            ->expects($this->any())
            ->method('isTransparentCheckoutActive')
            ->willReturn(true);

        $boletoModel
            ->expects($this->any())
            ->method('getActiveTransparentPaymentMethod')
            ->willReturn('pagarme_creditcard,pagarme_bowleto');

        $this->assertTrue($boletoModel->isAvailable());
    }
}
