<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use PagarMe\Magento\Test\Helper\PaymentMethodSettingsProvider;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class PaymentMethodAvailabilityContext extends MinkContext
{
    private static $checkoutBlock;

    /**
     * @BeforeSuite
     */
    public static function setUp()
    {
        self::$checkoutBlock = new \PagarMe_Checkout_Block_Form_Checkout();
    }

    /**
     * @Given a payment method :paymentMethod
     */
    public function aPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @When I disable this payment method
     */
    public function iDisableThisPaymentMethod()
    {
        PaymentMethodSettingsProvider::setPaymentMethodsAvailable(
            PaymentMethodSettingsProvider::CREDIT_CARD
        );
    }

    /**
     * @Then the payment method must be disabled
     */
    public function thePaymentMethodMustBeDisabled()
    {
        \PHPUnit_Framework_TestCase::assertNotContains(
            PaymentMethodSettingsProvider::BOLETO,
            self::$checkoutBlock->getAvailablePaymentMethods()
        );
    }

    /**
     * @When I enable this payment method
     */
    public function iEnableThisPaymentMethod()
    {
        PaymentMethodSettingsProvider::setPaymentMethodsAvailable(
            PaymentMethodSettingsProvider::BOLETO
        );
    }

    /**
     * @Then the payment method must be enabled
     */
    public function thePaymentMethodMustBeEnabled()
    {
        \PHPUnit_Framework_TestCase::assertContains(
            PaymentMethodSettingsProvider::BOLETO,
            self::$checkoutBlock->getAvailablePaymentMethods()
        );
    }

    /**
     * @Given the payment methods boleto and credit card
     */
    public function thePaymentMethodsBoletoAndCreditCard()
    {
        $this->paymentMethod = PaymentMethodSettingsProvider::CREDIT_CARD_AND_BOLETO;
    }

    /**
     * @When I enable both payment methods
     */
    public function iEnableBothPaymentMethods()
    {
        PaymentMethodSettingsProvider::setPaymentMethodsAvailable(
            PaymentMethodSettingsProvider::CREDIT_CARD_AND_BOLETO
        );
    }

    /**
     * @Then both payment methods must be enabled
     */
    public function bothPaymentMethodsMustBeEnabled()
    {
        \PHPUnit_Framework_TestCase::assertEquals(
            PaymentMethodSettingsProvider::CREDIT_CARD_AND_BOLETO,
            self::$checkoutBlock->getAvailablePaymentMethods()
        );
    }

    /**
     * @AfterScenario
     */
    public static function tearDown()
    {
        PaymentMethodSettingsProvider::setPaymentMethodsAvailable(
            PaymentMethodSettingsProvider::CREDIT_CARD_AND_BOLETO
        );
    }
}
