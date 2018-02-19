<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */
    /**
     * @Given a product created
     */
     public function aProductCreated()
     {
        throw new \Codeception\Exception\Incomplete("Step `a product created` is not defined");
     }

    /**
     * @When I add the product to the cart
     */
     public function iAddTheProductToTheCart()
     {
        throw new \Codeception\Exception\Incomplete("Step `I add the product to the cart` is not defined");
     }

    /**
     * @When I go to the checkout page
     */
     public function iGoToTheCheckoutPage()
     {
        throw new \Codeception\Exception\Incomplete("Step `I go to the checkout page` is not defined");
     }

    /**
     * @When I fill all my informations
     */
     public function iFillAllMyInformations()
     {
        throw new \Codeception\Exception\Incomplete("Step `I fill all my informations` is not defined");
     }

    /**
     * @When place the order
     */
     public function placeTheOrder()
     {
        throw new \Codeception\Exception\Incomplete("Step `place the order` is not defined");
     }

    /**
     * @Then I should be in the success page
     */
     public function iShouldBeInTheSuccessPage()
     {
        throw new \Codeception\Exception\Incomplete("Step `I should be in the success page` is not defined");
     }

    /**
     * @Given a registered user
     */
     public function aRegisteredUser()
     {
        throw new \Codeception\Exception\Incomplete("Step `a registered user` is not defined");
     }

    /**
     * @When I access the store page
     */
     public function iAccessTheStorePage()
     {
        throw new \Codeception\Exception\Incomplete("Step `I access the store page` is not defined");
     }

    /**
     * @When add any product to basket
     */
     public function addAnyProductToBasket()
     {
        throw new \Codeception\Exception\Incomplete("Step `add any product to basket` is not defined");
     }

    /**
     * @When I go to checkout page
     */
     public function iGoToCheckoutPage()
     {
        throw new \Codeception\Exception\Incomplete("Step `I go to checkout page` is not defined");
     }

    /**
     * @When login with registered user
     */
     public function loginWithRegisteredUser()
     {
        throw new \Codeception\Exception\Incomplete("Step `login with registered user` is not defined");
     }

    /**
     * @When confirm billing and shipping address information
     */
     public function confirmBillingAndShippingAddressInformation()
     {
        throw new \Codeception\Exception\Incomplete("Step `confirm billing and shipping address information` is not defined");
     }

    /**
     * @When choose pay with transparent checkout using boleto
     */
     public function choosePayWithTransparentCheckoutUsingBoleto()
     {
        throw new \Codeception\Exception\Incomplete("Step `choose pay with transparent checkout using boleto` is not defined");
     }

    /**
     * @When I confirm my payment information
     */
     public function iConfirmMyPaymentInformation()
     {
        throw new \Codeception\Exception\Incomplete("Step `I confirm my payment information` is not defined");
     }

    /**
     * @When place order
     */
     public function placeOrder()
     {
        throw new \Codeception\Exception\Incomplete("Step `place order` is not defined");
     }

    /**
     * @Then the purchase must be paid with success
     */
     public function thePurchaseMustBePaidWithSuccess()
     {
        throw new \Codeception\Exception\Incomplete("Step `the purchase must be paid with success` is not defined");
     }

    /**
     * @Then a link to boleto must be provided
     */
     public function aLinkToBoletoMustBeProvided()
     {
        throw new \Codeception\Exception\Incomplete("Step `a link to boleto must be provided` is not defined");
     }

    /**
     * @When choose pay with pagar me checkout using :arg1
     */
     public function choosePayWithPagarMeCheckoutUsing($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `choose pay with pagar me checkout using :arg1` is not defined");
     }

    /**
     * @When I confirm my personal data
     */
     public function iConfirmMyPersonalData()
     {
        throw new \Codeception\Exception\Incomplete("Step `I confirm my personal data` is not defined");
     }

    /**
     * @When finish payment process
     */
     public function finishPaymentProcess()
     {
        throw new \Codeception\Exception\Incomplete("Step `finish payment process` is not defined");
     }

    /**
     * @Given a fixed_value discount of :num1
     */
     public function aFixed_valueDiscountOf($num1)
     {
        throw new \Codeception\Exception\Incomplete("Step `a fixed_value discount of :num1` is not defined");
     }

    /**
     * @Then the discount must be described in checkout
     */
     public function theDiscountMustBeDescribedInCheckout()
     {
        throw new \Codeception\Exception\Incomplete("Step `the discount must be described in checkout` is not defined");
     }

    /**
     * @Then the discount must be applied
     */
     public function theDiscountMustBeApplied()
     {
        throw new \Codeception\Exception\Incomplete("Step `the discount must be applied` is not defined");
     }

    /**
     * @Given a percentage discount of :num1
     */
     public function aPercentageDiscountOf($num1)
     {
        throw new \Codeception\Exception\Incomplete("Step `a percentage discount of :num1` is not defined");
     }

    /**
     * @Given a valid credit card
     */
     public function aValidCreditCard()
     {
        throw new \Codeception\Exception\Incomplete("Step `a valid credit card` is not defined");
     }

    /**
     * @When I confirm my payment information with :arg1 installments
     */
     public function iConfirmMyPaymentInformationWithInstallments($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I confirm my payment information with :arg1 installments` is not defined");
     }

    /**
     * @Then the interest must applied
     */
     public function theInterestMustApplied()
     {
        throw new \Codeception\Exception\Incomplete("Step `the interest must applied` is not defined");
     }

    /**
     * @Then the interest must be described in checkout
     */
     public function theInterestMustBeDescribedInCheckout()
     {
        throw new \Codeception\Exception\Incomplete("Step `the interest must be described in checkout` is not defined");
     }

    /**
     * @Then The button that opens pagarme checkout must be hidden
     */
     public function theButtonThatOpensPagarmeCheckoutMustBeHidden()
     {
        throw new \Codeception\Exception\Incomplete("Step `The button that opens pagarme checkout must be hidden` is not defined");
     }

    /**
     * @Given a admin user
     */
     public function aAdminUser()
     {
        throw new \Codeception\Exception\Incomplete("Step `a admin user` is not defined");
     }

    /**
     * @Given a api key
     */
     public function aApiKey()
     {
        throw new \Codeception\Exception\Incomplete("Step `a api key` is not defined");
     }

    /**
     * @Given a encryption key
     */
     public function aEncryptionKey()
     {
        throw new \Codeception\Exception\Incomplete("Step `a encryption key` is not defined");
     }

    /**
     * @When I access the admin
     */
     public function iAccessTheAdmin()
     {
        throw new \Codeception\Exception\Incomplete("Step `I access the admin` is not defined");
     }

    /**
     * @When go to system configuration page
     */
     public function goToSystemConfigurationPage()
     {
        throw new \Codeception\Exception\Incomplete("Step `go to system configuration page` is not defined");
     }

    /**
     * @When insert an API key
     */
     public function insertAnAPIKey()
     {
        throw new \Codeception\Exception\Incomplete("Step `insert an API key` is not defined");
     }

    /**
     * @When insert an encryption key
     */
     public function insertAnEncryptionKey()
     {
        throw new \Codeception\Exception\Incomplete("Step `insert an encryption key` is not defined");
     }

    /**
     * @When save configuration
     */
     public function saveConfiguration()
     {
        throw new \Codeception\Exception\Incomplete("Step `save configuration` is not defined");
     }

    /**
     * @Then the configuration must be saved with success
     */
     public function theConfigurationMustBeSavedWithSuccess()
     {
        throw new \Codeception\Exception\Incomplete("Step `the configuration must be saved with success` is not defined");
     }

    /**
     * @When enable Pagar:num1me Checkout
     */
     public function enablePagarmeCheckout($num1)
     {
        throw new \Codeception\Exception\Incomplete("Step `enable Pagar:num1me Checkout` is not defined");
     }

    /**
     * @Then Pagar:num1me checkout must be enabled
     */
     public function pagarmeCheckoutMustBeEnabled($num1)
     {
        throw new \Codeception\Exception\Incomplete("Step `Pagar:num1me checkout must be enabled` is not defined");
     }

    /**
     * @When turn on customer data capture
     */
     public function turnOnCustomerDataCapture()
     {
        throw new \Codeception\Exception\Incomplete("Step `turn on customer data capture` is not defined");
     }

    /**
     * @When change the boleto helper text
     */
     public function changeTheBoletoHelperText()
     {
        throw new \Codeception\Exception\Incomplete("Step `change the boleto helper text` is not defined");
     }

    /**
     * @When change the credit card helper text
     */
     public function changeTheCreditCardHelperText()
     {
        throw new \Codeception\Exception\Incomplete("Step `change the credit card helper text` is not defined");
     }

    /**
     * @When change the ui color
     */
     public function changeTheUiColor()
     {
        throw new \Codeception\Exception\Incomplete("Step `change the ui color` is not defined");
     }

    /**
     * @When change the header text
     */
     public function changeTheHeaderText()
     {
        throw new \Codeception\Exception\Incomplete("Step `change the header text` is not defined");
     }

    /**
     * @When change the payment button text
     */
     public function changeThePaymentButtonText()
     {
        throw new \Codeception\Exception\Incomplete("Step `change the payment button text` is not defined");
     }

    /**
     * @When change the checkout button text
     */
     public function changeTheCheckoutButtonText()
     {
        throw new \Codeception\Exception\Incomplete("Step `change the checkout button text` is not defined");
     }

    /**
     * @When change payment method title
     */
     public function changePaymentMethodTitle()
     {
        throw new \Codeception\Exception\Incomplete("Step `change payment method title` is not defined");
     }

    /**
     * @Given Pagar:num1me settings panel
     */
     public function pagarmeSettingsPanel($num1)
     {
        throw new \Codeception\Exception\Incomplete("Step `Pagar:num1me settings panel` is not defined");
     }

    /**
     * @When I set interest rate to :arg1
     */
     public function iSetInterestRateTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set interest rate to :arg1` is not defined");
     }

    /**
     * @When I set free instalments to :arg1
     */
     public function iSetFreeInstalmentsTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set free instalments to :arg1` is not defined");
     }

    /**
     * @When I set max instalments to :arg1
     */
     public function iSetMaxInstalmentsTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set max instalments to :arg1` is not defined");
     }

    /**
     * @Given a credit card list to allow
     */
     public function aCreditCardListToAllow()
     {
        throw new \Codeception\Exception\Incomplete("Step `a credit card list to allow` is not defined");
     }

    /**
     * @When select the allowed credit cards
     */
     public function selectTheAllowedCreditCards()
     {
        throw new \Codeception\Exception\Incomplete("Step `select the allowed credit cards` is not defined");
     }

    /**
     * @Then the credit card list must be saved in database
     */
     public function theCreditCardListMustBeSavedInDatabase()
     {
        throw new \Codeception\Exception\Incomplete("Step `the credit card list must be saved in database` is not defined");
     }

    /**
     * @When I set boleto discount to :arg1
     */
     public function iSetBoletoDiscountTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set boleto discount to :arg1` is not defined");
     }

    /**
     * @When I set boleto discount mode to :arg1
     */
     public function iSetBoletoDiscountModeTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set boleto discount mode to :arg1` is not defined");
     }

    /**
     * @When choose pay with transparent checkout using credit card
     */
     public function choosePayWithTransparentCheckoutUsingCreditCard()
     {
        throw new \Codeception\Exception\Incomplete("Step `choose pay with transparent checkout using credit card` is not defined");
     }

    /**
     * @When I set max installments to :arg1
     */
     public function iSetMaxInstallmentsTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set max installments to :arg1` is not defined");
     }

    /**
     * @When I should see only installment options up to :arg1
     */
     public function iShouldSeeOnlyInstallmentOptionsUpTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I should see only installment options up to :arg1` is not defined");
     }

    /**
     * @When I set max installments to :num1:num2
     */
     public function iSetMaxInstallmentsTo($num1, $num2)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set max installments to :num1:num2` is not defined");
     }

    /**
     * @When I set interest rate to :num1:num2
     */
     public function iSetInterestRateTo($num1, $num2)
     {
        throw new \Codeception\Exception\Incomplete("Step `I set interest rate to :num1:num2` is not defined");
     }

    /**
     * @When I choose :num1:num2
     */
     public function iChoose($num1, $num2)
     {
        throw new \Codeception\Exception\Incomplete("Step `I choose :num1:num2` is not defined");
     }

    /**
     * @Then the purchase must be created with value based on both :num:num3:num2 and :num:num3:num2
     */
     public function thePurchaseMustBeCreatedWithValueBasedOnBothAnd($num1, $num2, $num3, $num4)
     {
        throw new \Codeception\Exception\Incomplete("Step `the purchase must be created with value based on both :num:num3:num2 and :num:num3:num2` is not defined");
     }

    /**
     * @Given a created order with installment value of :arg1 and interest of :arg1
     */
     public function aCreatedOrderWithInstallmentValueOfAndInterestOf($arg1, $arg2)
     {
        throw new \Codeception\Exception\Incomplete("Step `a created order with installment value of :arg1 and interest of :arg1` is not defined");
     }

    /**
     * @When I check the order interest amount in its detail page
     */
     public function iCheckTheOrderInterestAmountInItsDetailPage()
     {
        throw new \Codeception\Exception\Incomplete("Step `I check the order interest amount in its detail page` is not defined");
     }

    /**
     * @Then the interest value should consider the values :arg1 and :arg1
     */
     public function theInterestValueShouldConsiderTheValuesAnd($arg1, $arg2)
     {
        throw new \Codeception\Exception\Incomplete("Step `the interest value should consider the values :arg1 and :arg1` is not defined");
     }

    /**
     * @When I login to the admin
     */
     public function iLoginToTheAdmin()
     {
        throw new \Codeception\Exception\Incomplete("Step `I login to the admin` is not defined");
     }

    /**
     * @When I check the order interest amount in its admin detail page
     */
     public function iCheckTheOrderInterestAmountInItsAdminDetailPage()
     {
        throw new \Codeception\Exception\Incomplete("Step `I check the order interest amount in its admin detail page` is not defined");
     }

    /**
     * @Then the admin interest value should consider the values :arg1 and :arg1
     */
     public function theAdminInterestValueShouldConsiderTheValuesAnd($arg1, $arg2)
     {
        throw new \Codeception\Exception\Incomplete("Step `the admin interest value should consider the values :arg1 and :arg1` is not defined");
     }

    /**
     * @Given I am on checkout page using Inovarti One Step Checkout
     */
     public function iAmOnCheckoutPageUsingInovartiOneStepCheckout()
     {
        throw new \Codeception\Exception\Incomplete("Step `I am on checkout page using Inovarti One Step Checkout` is not defined");
     }

    /**
     * @When I confirm payment via :arg1 with :arg2 installments
     */
     public function iConfirmPaymentViaWithInstallments($arg1, $arg2)
     {
        throw new \Codeception\Exception\Incomplete("Step `I confirm payment via :arg1 with :arg2 installments` is not defined");
     }

    /**
     * @Then the purchase must be created with success
     */
     public function thePurchaseMustBeCreatedWithSuccess()
     {
        throw new \Codeception\Exception\Incomplete("Step `the purchase must be created with success` is not defined");
     }

    /**
     * @Given fixed :arg1 discount for boleto payment is provided
     */
     public function fixedDiscountForBoletoPaymentIsProvided($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `fixed :arg1 discount for boleto payment is provided` is not defined");
     }

    /**
     * @Then the absolute discount of :arg1 must be informed on checkout
     */
     public function theAbsoluteDiscountOfMustBeInformedOnCheckout($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `the absolute discount of :arg1 must be informed on checkout` is not defined");
     }

    /**
     * @Given percentual :arg1 discount for boleto payment is provided
     */
     public function percentualDiscountForBoletoPaymentIsProvided($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `percentual :arg1 discount for boleto payment is provided` is not defined");
     }

    /**
     * @Then the percentual discount of :arg1 must be informed on checkout
     */
     public function thePercentualDiscountOfMustBeInformedOnCheckout($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `the percentual discount of :arg1 must be informed on checkout` is not defined");
     }

    /**
     * @Given :arg1 interest rate for multi installment payment
     */
     public function interestRateForMultiInstallmentPayment($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `:arg1 interest rate for multi installment payment` is not defined");
     }

    /**
     * @When I confirm payment using :arg1 installments
     */
     public function iConfirmPaymentUsingInstallments($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I confirm payment using :arg1 installments` is not defined");
     }

    /**
     * @Then the percentual interest of :arg1 over :arg2 installments must be informed on checkout
     */
     public function thePercentualInterestOfOverInstallmentsMustBeInformedOnCheckout($arg1, $arg2)
     {
        throw new \Codeception\Exception\Incomplete("Step `the percentual interest of :arg1 over :arg2 installments must be informed on checkout` is not defined");
     }

    /**
     * @When I confirm payment
     */
     public function iConfirmPayment()
     {
        throw new \Codeception\Exception\Incomplete("Step `I confirm payment` is not defined");
     }

    /**
     * @When select Pagar:num1me Checkout as payment method
     */
     public function selectPagarmeCheckoutAsPaymentMethod($num1)
     {
        throw new \Codeception\Exception\Incomplete("Step `select Pagar:num1me Checkout as payment method` is not defined");
     }

    /**
     * @When click on place order button
     */
     public function clickOnPlaceOrderButton()
     {
        throw new \Codeception\Exception\Incomplete("Step `click on place order button` is not defined");
     }

    /**
     * @Then an alert box must be displayed
     */
     public function anAlertBoxMustBeDisplayed()
     {
        throw new \Codeception\Exception\Incomplete("Step `an alert box must be displayed` is not defined");
     }

    /**
     * @Then I should see payment method equals to :arg1
     */
     public function iShouldSeePaymentMethodEqualsTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `I should see payment method equals to :arg1` is not defined");
     }

    /**
     * @Then installments equals to :arg1
     */
     public function installmentsEqualsTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `installments equals to :arg1` is not defined");
     }

    /**
     * @Given a payment method boleto
     */
     public function aPaymentMethodBoleto()
     {
        throw new \Codeception\Exception\Incomplete("Step `a payment method boleto` is not defined");
     }

    /**
     * @When I disable this payment method
     */
     public function iDisableThisPaymentMethod()
     {
        throw new \Codeception\Exception\Incomplete("Step `I disable this payment method` is not defined");
     }

    /**
     * @Then the payment method must be disabled
     */
     public function thePaymentMethodMustBeDisabled()
     {
        throw new \Codeception\Exception\Incomplete("Step `the payment method must be disabled` is not defined");
     }

    /**
     * @When I enable this payment method
     */
     public function iEnableThisPaymentMethod()
     {
        throw new \Codeception\Exception\Incomplete("Step `I enable this payment method` is not defined");
     }

    /**
     * @Then the payment method must be enabled
     */
     public function thePaymentMethodMustBeEnabled()
     {
        throw new \Codeception\Exception\Incomplete("Step `the payment method must be enabled` is not defined");
     }

    /**
     * @Given the payment methods boleto and credit card
     */
     public function thePaymentMethodsBoletoAndCreditCard()
     {
        throw new \Codeception\Exception\Incomplete("Step `the payment methods boleto and credit card` is not defined");
     }

    /**
     * @When I enable both payment methods
     */
     public function iEnableBothPaymentMethods()
     {
        throw new \Codeception\Exception\Incomplete("Step `I enable both payment methods` is not defined");
     }

    /**
     * @Then both payment methods must be enabled
     */
     public function bothPaymentMethodsMustBeEnabled()
     {
        throw new \Codeception\Exception\Incomplete("Step `both payment methods must be enabled` is not defined");
     }

    /**
     * @Given a pending boleto order
     */
     public function aPendingBoletoOrder()
     {
        throw new \Codeception\Exception\Incomplete("Step `a pending boleto order` is not defined");
     }

    /**
     * @When a :arg1 order be paid
     */
     public function aOrderBePaid($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `a :arg1 order be paid` is not defined");
     }

    /**
     * @Then the order status must be updated to :arg1
     */
     public function theOrderStatusMustBeUpdatedTo($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `the order status must be updated to :arg1` is not defined");
     }

    /**
     * @When the :arg1 payment be refunded
     */
     public function thePaymentBeRefunded($arg1)
     {
        throw new \Codeception\Exception\Incomplete("Step `the :arg1 payment be refunded` is not defined");
     }

    /**
     * @Given a pending credit card order
     */
     public function aPendingCreditCardOrder()
     {
        throw new \Codeception\Exception\Incomplete("Step `a pending credit card order` is not defined");
     }
}
