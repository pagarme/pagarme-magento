<?php

use Behat\MinkExtension\Context\RawMinkContext;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class CheckoutContext extends RawMinkContext
{
    use PagarMe\Magento\Test\Helper\PagarMeSettings;
    use PagarMe\Magento\Test\Helper\CustomerDataProvider;
    use PagarMe\Magento\Test\Helper\ProductDataProvider;
    use PagarMe\Magento\Test\Helper\PagarMeSwitch;
    use PagarMe\Magento\Test\Helper\Configuration\Inovarti;
    use PagarMe\Magento\Test\Helper\SessionWait;

    private $customer;

    private $session;

    private $grandTotal;

    private $pagarMeCheckout;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $config = Mage::getModel('core/config');
        $config->saveConfig(
            'payment/pagarme_configurations/modal_payment_methods',
            'credit_card,boleto'
        );

        $config->saveConfig(
            'payment/pagarme_configurations/creditcard_interest_rate',
            5
        );

        $config->saveConfig(
            'payment/pagarme_configurations/creditcard_max_installments',
            12
        );

        $this->disableInovartiOneStepCheckout();

        $this->magentoUrl = getenv('MAGENTO_URL');
        $this->session = $this->getSession();
        $this->product = $this->getProduct();
        $this->product->save();

        $stock = $this->getProductStock();
        $stock->assignProduct($this->product);
        $stock->save();

        $this->disablePagarmeTransparent();
        $this->enablePagarmeCheckout();
    }

    /**
     * @Given a registered user
     */
    public function aRegisteredUser()
    {
        $this->customer = $this->getCustomer();
        $this->customer->save();

        $this->customerAddress = $this->getCustomerAddress();
        $this->customerAddress->setCustomerId($this->customer->getId());
        $this->customerAddress->save();
    }

    /**
     * @Given a valid credit card
     */
    public function aValidCreditCard()
    {
        $this->creditCard = [
            'customer_name'   => $this->customer->getName(),
            'number'          => '4111111111111111',
            'cvv'             => '123',
            'expiration_date' => '0220'
        ];
    }

    /**
     * @When I access the store page
     */
    public function iAccessTheStorePage()
    {
        $this->session
            ->visit($this->magentoUrl);
    }

    /**
     * @When add any product to basket
     */
    public function addAnyProductToBasket()
    {
        $page = $this->session->getPage();

        $page->clickLink($this->product->getName());

        $page->pressButton(
            Mage::helper('pagarme_modal')->__('Add to Cart')
        );
    }

    /**
     * @When i go to checkout page
     */
    public function iGoToCheckoutPage()
    {
        $page = $this->session->getPage();

        $page->pressButton(
            Mage::helper('pagarme_modal')->__('Proceed to Checkout')
        );
    }

    /**
     * @When login with registered user
     */
    public function loginWithRegisteredUser()
    {
        $page = $this->session->getPage();

        $this->getSession()->getPage()->fillField(
            Mage::helper('pagarme_modal')->__('Email Address'),
            $this->customer->getEmail()
        );

        $this->getSession()->getPage()->fillField(
            Mage::helper('pagarme_modal')->__('Password'),
            $this->customer->getPassword()
        );

        $page->pressButton('Login');
    }

    /**
     * @When confirm billing and shipping address information
     */
    public function confirmBillingAndShippingAddressInformation()
    {
        $page = $this->session->getPage();
        $page->find('css', '#billing-buttons-container button')->press();

        $this->waitForElement('#checkout-step-shipping_method', 5000);

        $page->find('css', '#shipping-method-buttons-container button')
            ->press();

        $this->waitForElement('#checkout-step-payment', 5000);

        $page->find('css', '#p_method_pagarme_modal')->click();

        $modalButtonText = Mage::getStoreConfig('payment/pagarme_configurations/modal_button_text');
        $defaultModalButtonText = $this->getDefaultSettings()['modal_button_text'];
        $modalButtonText = empty($modalButtonText) ? $defaultModalButtonText : $modalButtonText;

        $page->pressButton($modalButtonText);
    }

     /**
     * @When choose pay with pagar me checkout using :paymentMethod
     */
    public function choosePayWithPagarMeCheckoutUsing($paymentMethod)
    {
        $page = $this->session->getPage();

        $this->session->switchToIframe(
            $page->find('css', 'iframe')->getAttribute('name')
        );

        $this->getSession()->wait(1000);

        $this->pagarMeCheckout = $this->session->getPage();
        $this->waitForElement(
            '.choose-method-button-container',
            2000
        );
        $this->pagarMeCheckout->pressButton($paymentMethod);
    }

    /**
     * @When I confirm my personal data
     */
    public function iConfirmMyPersonalData()
    {
        $this->waitForElement(
            '#pagarme-modal-box-step-buyer-information',
            1000
        );

        $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-step-buyer-information .pagarme-modal-box-next-step'
        )->click();

        $this->waitForElement(
            '#pagarme-modal-box-step-customer-address-information',
            1000
        );

        $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-step-customer-address-information .pagarme-modal-box-next-step'
        )->click();
    }

    /**
     * @When I confirm my payment information with :number installments
     */
    public function iConfirmMyPaymentInformationWithInstallments($installmentsNumber)
    {
        $this->waitForElement(
            '#pagarme-modal-box-step-credit-card-information',
            1000
        );

        $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-credit-card-number'
        )->setValue($this->creditCard['number']);

        $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-credit-card-name'
        )->setValue($this->creditCard['customer_name']);

        $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-credit-card-expiration'
        )->setValue($this->creditCard['expiration_date']);

        $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-credit-card-cvv'
        )->setValue($this->creditCard['cvv']);

        $this->waitForElement(
            '#pagarme-modal-box-installments',
            3000
        );

        $installmentSelector = $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-installments'
        );

        if ($installmentSelector) {
            $field = $this->pagarMeCheckout->find(
                'css',
                "[data-value='$installmentsNumber']"
            );
            $field->click();
            $this->grandTotal = $field->getAttribute('data-amount');
        }


        $this->pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-step-credit-card-information .pagarme-modal-box-next-step'
        )->click();

        $this->session->wait(2000);
    }

    /**
     * @Then finish payment process
     */
    public function finishPaymentProcess()
    {
        $this->session->switchToIframe();

        $this->session->wait(
            5000,
            "document.querySelector('#pagarme-checkout-container').style.display == 'none'"
        );

        $this->session->getPage()->find(
            'css',
            '#payment-buttons-container button'
        )->press();

        $this->waitForElement('#checkout-step-review', 8000);
    }

    /**
     * @Then place order
     * @And place order
     */
    public function placeOrder()
    {
        $page = $this->session->getPage();
        $page->pressButton(Mage::helper('pagarme_modal')->__('Place Order'));
    }

     /**
     * @Then the purchase must be paid with success
     */
    public function thePurchaseMustBePaidWithSuccess()
    {
        $this->session->wait(10000);

        $page = $this->session->getPage();

        $successMessage = $page->find('css', 'h1')
            ->getText();

        \PHPUnit_Framework_TestCase::assertEquals(
            getenv('MAGENTO_URL') . 'index.php/checkout/onepage/success/',
            $this->session->getCurrentUrl()
        );

        \PHPUnit_Framework_TestCase::assertEquals(
            strtolower(
                Mage::helper(
                    'pagarme_modal'
                )->__('Your order has been received.')
            ),
            strtolower($successMessage)
        );
    }

    /**
     * @Then the interest must applied
     */
    public function theInterestMustApplied()
    {
        $this->session->wait(10000);
        $pricesCell = $this->session->getPage()->find('css', 'tfoot strong span.price');

        \PHPUnit_Framework_TestCase::assertEquals(
            $this->grandTotal,
            filter_var($pricesCell->getHtml(), FILTER_SANITIZE_NUMBER_FLOAT)
        );
    }

    /**
     * @Then the interest must be described in checkout
     */
    public function theInterestMustBeDescribedInCheckout()
    {
        \PHPUnit_Framework_TestCase::assertContains(
            Mage::helper('pagarme_modal')->__('Interest'),
            $this->getSession()->getPage()->getText()
        );
    }

    /**
     * @Then a link to boleto must be provided
     */
    public function aLinkToBoletoMustBeProvided()
    {
        $page = $this->session->getPage();

        \PHPUnit_Framework_TestCase::assertContains(
            Mage::helper('pagarme_boleto')->__('Click the followed link to print your boleto'),
            $page->find(
                'css',
                '.pagarme_boleto_info_boleto'
            )->getText()
        );

        \PHPUnit_Framework_TestCase::assertContains(
            'https://pagar.me',
            $page ->find(
                'css',
                '.pagarme_boleto_info_boleto a'
            )->getAttribute('href')
        );
    }

    /**
     * @Given a :discountMode discount of :discount
     */
    public function discountOf(
        $discountMode,
        $discount
    ) {
        $this->configuredDiscountMode = $discountMode;
        $this->configuredDiscount = $discount;

        Mage::getModel('core/config')
            ->saveConfig(
                'payment/pagarme_configurations/boleto_discount_mode',
                $this->configuredDiscountMode
            );

        Mage::getModel('core/config')
            ->saveConfig(
                'payment/pagarme_configurations/boleto_discount',
                $this->configuredDiscount
            );
    }

    /**
     * @Then the discount must be described in checkout
     */
    public function theDiscountMustBeDescribedInCheckout()
    {
        \PHPUnit_Framework_TestCase::assertContains(
            Mage::helper('pagarme_modal')->__('Discount'),
            $this->getSession()->getPage()->getText()
        );
    }

    private function getGrandTotal()
    {
        $grandTotalCell = $this->session->getPage()->find(
            'css',
            'tfoot tr.last strong span.price'
        );
        
        $grandTotal = filter_var(
            $grandTotalCell->getHtml(),
            FILTER_SANITIZE_NUMBER_FLOAT
        );

        return $grandTotal;
    }

    private function getSubtotal($ignoredValues = array())
    {
        $subtotal = 0;
        $prices = $this->session->getPage()->findAll('css', 'tfoot tr');

        foreach ($prices as $price) {
            $label = trim($price->find('css', ':first-child')->getText());
            $value = filter_var(
                $price->find('css', 'td.last')->getHtml(),
                FILTER_SANITIZE_NUMBER_FLOAT
            );

            if (in_array($label, $ignoredValues)
                || $label == \Mage::helper('core')->__('Grand Total')) {
                continue;
            }

            $subtotal += $value;
        }

        return $subtotal;
    }

    private function getItemValue($item)
    {
        $itemLabelElement = $this->session->getPage()->find(
            'css',
            'tfoot tr td:contains("'. $item .'"), tfoot tr th:contains("'. $item . '")'
        );

        $itemRowElement = $itemLabelElement->getParent();
        $itemValue = $itemRowElement->find('css', '.last .price');

        return filter_var(
            $itemValue->getHtml(),
            FILTER_SANITIZE_NUMBER_FLOAT
        );
    }

    /**
     * @Then the discount must be applied
     */
    public function theDiscountMustBeApplied()
    {
        $discountLabel = \Mage::helper('pagarme_core')->__('Discount');

        $subtotal = $this->getSubtotal([ $discountLabel ]);
        $discount = (int) $this->getItemValue($discountLabel);
        $grandTotal = $this->getGrandTotal();

        if ($this->configuredDiscountMode ==
            PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode::FIXED_VALUE) {
            $expectedGrandTotal = $subtotal + $discount;
            $expectedDiscountValue = \Mage::helper('pagarme_core')
                ->parseAmountToInteger($this->configuredDiscount) * -1;
        } else if ($this->configuredDiscountMode ==
            PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode::PERCENTAGE) {
            $expectedGrandTotal = ceil($subtotal * (1 - ($this->configuredDiscount / 100)));
            $expectedDiscountValue = ((int) ($subtotal * ($this->configuredDiscount / 100))) * -1;
        }

        \PHPUnit_Framework_TestCase::assertEquals(
            $expectedGrandTotal,
            $grandTotal
        );

        \PHPUnit_Framework_TestCase::assertEquals(
            $expectedDiscountValue,
            $discount
        );
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        $this->customer->delete();
        $this->product->delete();
        $this->restorePagarMeSettings();
    }

    /**
     * @Then The button that opens pagarme checkout must be hidden
     */
    public function theButtonThatOpensPagarmeCheckoutMustBeHidden()
    {
        $checkoutButton = $this->getSession()->getPage()->find(
            'css',
            '#pagarme-modal-fill-info-button'
        );
        \PHPUnit_Framework_TestCase::assertEquals(
            $checkoutButton,
            NULL
        );
    }

}
