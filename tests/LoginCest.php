<?php

use Codeception\Util\ActionSequence;

class LoginCest 
{    
    public function _before(AcceptanceTester $I)
    {
      $this->iAccessTheProductPage('qwe', $I);
      $this->iAddProductToCart('qwe', $I);
      $this->iGoToTheCheckout($I);
      $this->chooseCheckoutAsGuest($I);
      $this->iCompleteMyPersonalInformation($I);
      $this->iChooseTheShippingMethod($I);
      $this->iFillPaymentOption($I);
      $this->iPlaceOrder($I);
    }
    

    private function iAccessTheProductPage(
      $productName,
      AcceptanceTester $I
    ) {
        $I->amOnPage('/');
        $I->performOn('#search', ActionSequence::build()
            ->fillField('#search', $productName)
        );
        $I->click('Search');
    }

    private function iAddProductToCart($productName, AcceptanceTester $I)
    {
      $I->performOn(
        "//*/li/h2/a[text()='${productName}']", [
          'click' => 'Add to Cart' 
        ]
      );
    }

    private function iGoToTheCheckout(AcceptanceTester $I)
    {
      $I->performOn(
        '.top-link-checkout', [
          'click' => '.top-link-checkout'
        ]
      );
    }

    private function chooseCheckoutAsGuest(AcceptanceTester $I)
    {
      $I->checkOption('#login:guest');
      $I->click('Continue');
    }

    private function iCompleteMyPersonalInformation(AcceptanceTester $I)
    {
      $I->fillField('#billing:firstname' , 'First');
      $I->fillField('#billing:lastname' , 'Last');
      $I->fillField('#billing:company' , 'Company');
      $I->fillField('#billing:email' , 'qwe@qwe.com');
      $I->fillField('#billing:street1' , 'Street 1');
      $I->fillField('#billing:street2' , 'Street 2');
      $I->fillField('#billing:street3' , 'Street 3');
      $I->fillField('#billing:street4' , 'Street 4');
      $I->selectOption('#billing:country_id' , 'Brazil');
      $I->fillField('#billing:city' , 'City');
      $I->fillField('#billing:region' , 'São Paulo');
      $I->fillField('#billing:telephone' , '(11)87654321');
      $I->fillField('#billing:taxvat' , '018.090.569-42');
      $I->fillField('#billing:postcode' , '64057-158');
      $I->checkOption('#billing:use_for_shipping_yes');
      $I->click('#billing-buttons-container button');
    }

    private function iChooseTheShippingMethod(AcceptanceTester $I)
    {
      $I->waitForElementVisible('#shipping-method-buttons-container button', 30);
      $I->makeScreenshot('click-shipping-method');
      $I->click('#shipping-method-buttons-container button');
    }

    private function iFillPaymentOption(AcceptanceTester $I)
    {
      $I->waitForElement('#p_method_checkmo');
      $I->checkOption('#p_method_checkmo');
      $I->click('#payment-buttons-container button');
    }

    private function iPlaceOrder(AcceptanceTester $I)
    {
      $I->waitForElement('#checkout-review-submit button');
      $I->click('#checkout-review-submit button');

    }

    private function waitPageLoad($timeout = 10)
    {
      $this->webDriverModule->waitForJs('return document.readyState == "complete"', $timeout);
      $this->waitAjaxLoad($timeout);
      $this->dontSeeJsError();
    }

    public function loginSuccessfully(AcceptanceTester $I)
    {
        $I->waitPageLoad(30);
        $I->seeInCurrentUrl('success');
        // write a positive login test 
    }
}
