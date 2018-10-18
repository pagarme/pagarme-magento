<?php

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use PagarMe\Magento\Test\Helper\AdminAccessProvider;
use PagarMe\Magento\Test\PagarMeMagentoContext;
use PagarMe\Magento\Test\Helper\SessionWait;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class BoletoAdminContext extends RawMinkContext
{
    use PagarMe\Magento\Test\Helper\SessionWait;

    /**
     * Default configuration for Pagar.me module
     */
    const CUSTOMER_STREET_LINES = '4';

    /**
     * @var string
     */
    private $magentoUrl;

    /**
     * @var \Behat\Mink\Element\DocumentElement;
     */
    private $page;

    public function __construct()
    {
        $this->magentoUrl = getenv('MAGENTO_URL');
    }

    /**
     * @BeforeScenario
     */
    public function setUpPage()
    {
        $this->page = $this->getSession()->getPage();
    }

    /**
     * @When /^I set the street line config to (\d+)$/
     */
    public function iSetTheStreetLineConfigTo()
    {
        Mage::getConfig()->saveConfig(
            'customer/address/street_lines',
            self::CUSTOMER_STREET_LINES
        );
        Mage::getConfig()->saveConfig('admin/security/use_form_key', 0);
        $this->getSession()->wait(500);
        $this->getSession()->visit(
            $this->magentoUrl . 'admin/system_config/edit/section/customer'
        );
        $this->getSession()->wait(2500);
        $this->page->pressButton('Save Config');

        Mage::getConfig()->saveConfig('admin/security/use_form_key', 1);
    }

    /**
     * @When I access the orders list page
     */
    public function iAccessTheOrdersListPage()
    {
        $this->getSession()->visit($this->magentoUrl . 'admin/sales_order');
    }

    /**
     * @When I click on create new order button
     */
    public function iClickOnCreateNewOrderButton()
    {
        $this->page->pressButton('Create New Order');
    }

    /**
     * @When I select a registered customer
     */
    public function iSelectARegisteredCustomer()
    {
        $page = $this->page;

        $this->spin(function() use ($page) {
            $customer = $page->find(
                'css',
                '#sales_order_create_customer_grid_table td.a-right'
            );

            return $customer != null;
        },3);

        $firstCustomerEntry = $this->page->find(
            'css',
            '#sales_order_create_customer_grid_table td.a-right'
        )->click();
    }

    /**
     * @When I add a product
     */
    public function iAddAProduct()
    {
        $page = $this->page;

        $this->spin(function() use ($page) {
            $addProductButton = $page->find(
                'css',
                '#order-items button.scalable.add:first-child'
            );

            return $addProductButton != null;
        }, 10);

        $this->page->pressButton('Add Products');

        $this->page->find(
            'css',
            '#sales_order_create_search_grid_table input.checkbox'
        )->press();

        $this->page->pressButton('Add Selected Product(s) to Order');
    }

    /**
     * @When I inform missing customer data
     */
    public function iInformMissingCustomerData()
    {
        $this->getSession()->wait(10000);

        $taxVatField = $this->page
            ->find('css', '#order-billing_address_vat_id')
            ->setValue('332.840.319-10');
    }

    /**
     * @When I select boleto as payment method
     */
    public function iSelectBoletoAsPaymentMethod()
    {
        $page = $this->page;

        $this->getSession()->wait(3500);

        $this->page->find('css', '#p_method_pagarme_bowleto')->click();
    }

    /**
     * @When I choose a shipping option
     */
    public function iChooseAShippingOption()
    {
        $page = $this->page;
        $this->getSession()->wait(5000);
        $page->find('css', '#order-shipping-method-summary a')->click();
        $this->getSession()->wait(5000);
        $page->find('css', '#s_method_flatrate_flatrate')->click();
        $this->getSession()->wait(5000);
    }

    /**
     * @When I click on submit order button
     */
    public function iClickOnSubmitOrderButton()
    {
        $page = $this->getSession()->getPage();
        $this->getSession()->wait(3500);
        $page->pressButton('Submit Order');
        $this->getSession()->wait(15000);
    }

    /**
     * @Then a new order should be created
     */
    public function aNewOrderShouldBeCreated()
    {
        $this->getSession()->wait(5000);

        $successMessageElement = $this->page
            ->find('named', ['content', 'The order has been created.']);

        if (is_null($successMessageElement)) {
            throw new \Exception(
                'Success message should be present on the page'
            );
        }

        PHPUnit_Framework_Assert::assertEquals(
            $successMessageElement->getText(),
            'The order has been created.'
        );
    }

    /**
     * @Then transaction id should be present on the page
     */
    public function shouldTransactionIdShouldBePresentOnThePage()
    {
        $paymentMethodRows = $this->page->findAll(
            'css',
            '#pagarme_order_info_payment_details tr'
        );

        $transactionId = (int) $paymentMethodRows[1]
            ->findAll('css', 'td')[1]
            ->getText();

        PHPUnit_Framework_Assert::assertGreaterThan(
            0,
            $transactionId
        );

        \Mage::getConfig()->saveConfig(
            'customer/address/street_lines',
             self::CUSTOMER_STREET_LINES
        )->saveCache();
    }
}
