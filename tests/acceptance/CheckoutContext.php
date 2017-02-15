<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class CheckoutContext extends MinkContext
{
    use PagarMe\Magento\Test\Helper\CustomerDataProvider;
    use PagarMe\Magento\Test\Helper\ProductDataProvider;

    private $customer;

    private $session;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $this->magentoUrl = getenv('MAGENTO_URL');
        $this->session = $this->getSession();
        $this->product = $this->getProduct();
        $this->product->save();

        $stock = $this->getProductStock();
        $stock->assignProduct($this->product);
        $stock->save();
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
     * @When I use a valid credit card to pay
     */
    public function iUseAValidCreditCardToPay()
    {
        $page = $this->session->getPage();

        $this->session->switchToIframe(
            $page->find('css' ,'iframe')->getAttribute('name')
        );

        $pagarMeCheckout = $this->session->getPage();

        $pagarMeCheckout->pressButton('Cartão de crédito');

        $this->waitForElement(
            '#pagarme-modal-box-step-buyer-information',
            1000
        );

        $this->fillField(
            'pagarme-modal-box-buyer-name',
            $this->customer->getName()
        );

        $this->fillField(
            'pagarme-modal-box-buyer-email',
            $this->customer->getEmail()
        );

        $this->fillField(
            'pagarme-modal-box-buyer-document-number',
            $this->customer->getTaxvat()
        );

        $this->fillField(
            'pagarme-modal-box-buyer-ddd',
            '11'
        );

        $this->fillField(
            'pagarme-modal-box-buyer-number',
            '995551668'
        );

        $pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-step-buyer-information .pagarme-modal-box-next-step'
        )->click();

        $this->waitForElement(
            '#pagarme-modal-box-step-customer-address-information',
            1000
        );

        $this->fillField(
            'pagarme-modal-box-customer-address-zipcode',
            $this->customerAddress->getPostcode()
        );

        $this->fillField(
            'pagarme-modal-box-customer-address-street',
            $this->customerAddress->getStreet()[0]
        );

        $this->fillField(
            'pagarme-modal-box-customer-address-number',
            $this->customerAddress->getStreet()[1]
        );

        $this->fillField(
            'pagarme-modal-box-customer-address-complementary',
            $this->customerAddress->getStreet()[2]
        );

        $this->fillField(
            'pagarme-modal-box-customer-address-neighborhood',
            $this->customerAddress->getStreet()[3]
        );

        $this->fillField(
            'pagarme-modal-box-customer-address-city',
            $this->customerAddress->getCity()
        );

        $this->fillField(
            'pagarme-modal-box-customer-address-state',
            $this->customerAddress->getState()
        );

        $pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-step-customer-address-information .pagarme-modal-box-next-step'
        )->click();

        $this->waitForElement(
            '#pagarme-modal-box-step-credit-card-information',
            1000
        );

        $this->fillField(
            'pagarme-modal-box-credit-card-number',
            $this->creditCard['number']
        );

        $pagarMeCheckout->fillField(
            'pagarme-modal-box-credit-card-name',
            $this->creditCard['customer_name']
        );

        $pagarMeCheckout->fillField(
            'pagarme-modal-box-credit-card-expiration',
            $this->creditCard['expiration_date']
        );

        $pagarMeCheckout->fillField(
            'pagarme-modal-box-credit-card-cvv',
            $this->creditCard['cvv']
        );

        $pagarMeCheckout->find(
            'css',
            '#pagarme-modal-box-step-credit-card-information .pagarme-modal-box-next-step'
        )->click();

        $this->session->switchToIframe();

        $this->session->wait(
            5000,
            "document.querySelector('#pagarme-checkout-container').style.display == 'none'"
        );

        $page->find(
            'css',
            '#payment-buttons-container button'
        )->press();

        $this->waitForElement('#checkout-step-review', 2000);

        $page->pressButton(Mage::helper('pagarme_checkout')->__('Place Order'));
    }

    /**
     * @Then the purchase must be paid with success
     */
    public function thePurchaseMustBePaidWithSuccess()
    {
        $this->session->wait(5000);

        $page = $this->session->getPage();

        $successMsg = $page->find('css', 'h1')
            ->getText();

        \PHPUnit_Framework_TestCase::assertEquals(
            getenv('MAGENTO_URL') . 'index.php/checkout/onepage/success/',
            $this->session->getCurrentUrl()
        );

        \PHPUnit_Framework_TestCase::assertEquals(
            Mage::helper(
                'pagarme_checkout')->__('Your order has been received.'
            ),
            $successMsg
        );
    }

    /**
     * @AfterScenario
     */
    public function tearDown() {
        $this->customer->delete();
        $this->product->delete();
    }
}
