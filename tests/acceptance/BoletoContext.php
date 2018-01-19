<?php

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Tester\Exception\PendingException;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class BoletoContext extends RawMinkContext
{
    use PagarMe\Magento\Test\Helper\PagarMeSettings;
    use PagarMe\Magento\Test\Helper\PagarMeSwitch;
    use PagarMe\Magento\Test\Helper\CustomerDataProvider;
    use PagarMe\Magento\Test\Helper\ProductDataProvider;
    use PagarMe\Magento\Test\Helper\SessionWait;
    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $config = Mage::getModel('core/config');
        $this->magentoUrl = getenv('MAGENTO_URL');
        $this->session = $this->getSession();
        $this->product = $this->getProduct();
        $this->product->save();
        $stock = $this->getProductStock();
        $stock->assignProduct($this->product);
        $stock->save();
        $this->enablePagarmeTransparent();
        $config = Mage::getModel('core/config');
        $config->saveConfig(
            'payment/pagarme_configurations/general_encryption_key',
            PAGARME_ENCRYPTION_KEY 
        );
        $config->saveConfig(
            'payment/pagarme_configurations/general_api_key',
            PAGARME_API_KEY 
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
        $page->clickLink($this->product->getName());
        $page->pressButton(
            Mage::helper('pagarme_modal')->__('Add to Cart')
        );
    }
    /**
     * @When I go to checkout page
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
        $page->fillField(
            Mage::helper('pagarme_modal')->__('Email Address'),
            $this->customer->getEmail()
        );
        $page->fillField(
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
    }
    /**
     * @When choose pay with transparent checkout using boleto 
     */
    public function choosePayWithTransparentCheckoutUsingBoleto()
    {
        $page = $this->session->getPage();
        $this->waitForElement('#checkout-step-payment', 5000);
        $page->find('css', '#p_method_pagarme_boleto')->click();
    }
    /**
     * @When I confirm my payment information
     */
    public function iConfirmMyPaymentInformation()
    {
        $this->session->getPage()->find(
            'css',
            '#payment-buttons-container button'
        )->click();
    }
    /**
     * @When place order
     */
    public function placeOrder()
    {
        $this->waitForElement('#checkout-step-review', 8000);
        $this->session
            ->getPage()
            ->pressButton(
                Mage::helper('pagarme_boleto')
                ->__('Place Order')
            );
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
                    'pagarme_boleto'
                )->__('Your order has been received.')
            ),
            strtolower($successMessage)
        );
    }
    /**
     * @AfterScenario
     */
    public function afterEveryScenario()
    {
        Mage::getSingleton('customer/session')->logout();
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
}
