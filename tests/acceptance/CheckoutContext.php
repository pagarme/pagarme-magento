<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class CheckoutContext extends MinkContext
{
    use PagarMe\Magento\Test\Helper\CustomerDataProvider;
    use PagarMe\Magento\Test\Helper\ProductDataProvider;
    use PagarMe\Magento\Test\Helper\PagarMeCheckoutSwitch;

    private $customer;

    private $session;

    private $pagarMeCheckout;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $config = Mage::getModel('core/config')
            ->saveConfig(
                'payment/pagarme_settings/payment_methods',
                'credit_card,boleto'
            );

        $this->magentoUrl = getenv('MAGENTO_URL');
        $this->session = $this->getSession();
        $this->product = $this->getProduct();
        $this->product->save();

        $stock = $this->getProductStock();
        $stock->assignProduct($this->product);
        $stock->save();

        $this->enablePagarmeCheckout();
    }

    public function waitForElement($element, $timeout)
    {
        $this->session->wait(
            $timeout,
            "document.querySelector('${element}').style.display != 'none'"
        );
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

        $page->pressButton(
            Mage::helper('pagarme_checkout')->__('Add to Cart')
        );
    }

    /**
     * @When i go to checkout page
     */
    public function iGoToCheckoutPage()
    {
        $page = $this->session->getPage();

        $page->pressButton(
            Mage::helper('pagarme_checkout')->__('Proceed to Checkout')
        );
    }

    /**
     * @When login with registered user
     */
    public function loginWithRegisteredUser()
    {
        $page = $this->session->getPage();

        $this->fillField(
            Mage::helper('pagarme_checkout')->__('Email Address'),
            $this->customer->getEmail()
        );

        $this->fillField(
            Mage::helper('pagarme_checkout')->__('Password'),
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

        $page->find('css', '#p_method_pagarme_checkout')->click();
        $page->pressButton(
            Mage::helper('pagarme_checkout')->__('Fill in the card data')
        );
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

        $this->pagarMeCheckout = $this->session->getPage();
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
     * @Then finish purchase
     */
    public function finishPurchase()
    {
        $this->session->switchToIframe();

        $this->session->wait(
            5000,
            "document.querySelector('#pagarme-checkout-container').style.display == 'none'"
        );

        $page = $this->session->getPage();

        $page->find(
            'css',
            '#payment-buttons-container button'
        )->press();

        $this->waitForElement('#checkout-step-review', 2000);

        $page->pressButton(Mage::helper('pagarme_checkout')->__('Place Order'));
    }

     /**
     * @Then the purchase must be created success
     */
    public function thePurchaseMustBeCreatedWithSuccess()
    {
        $this->session->wait(5000);

        $page = $this->session->getPage();

        $successMessage = $page->find('css', 'h1')
            ->getText();

        \PHPUnit_Framework_TestCase::assertEquals(
            getenv('MAGENTO_URL') . 'index.php/checkout/onepage/success/',
            $this->session->getCurrentUrl()
        );

        \PHPUnit_Framework_TestCase::assertEquals(
            Mage::helper(
                'pagarme_checkout'
            )->__('Your order has been received.'),
            $successMessage
        );
    }

    /**
     * @Then a link to boleto must be provided
     */
    public function aLinkToBoletoMustBeProvided()
    {
        $page = $this->session->getPage();

        \PHPUnit_Framework_TestCase::assertContains(
            'Para imprimir o boleto',
            $page->find(
                'css',
                '.pagarme_info_boleto'
            )->getText()
        );

        \PHPUnit_Framework_TestCase::assertContains(
            'https://pagar.me',
            $page ->find(
                'css',
                '.pagarme_info_boleto a'
            )->getAttribute('href')
        );
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        $this->customer->delete();
        $this->product->delete();
        $this->disablePagarmeCheckout();
    }
}
