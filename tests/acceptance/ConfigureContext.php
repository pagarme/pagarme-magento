<?php

use Behat\MinkExtension\Context\RawMinkContext;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class ConfigureContext extends RawMinkContext
{
    use PagarMe\Magento\Test\Helper\CustomerDataProvider;
    use PagarMe\Magento\Test\Helper\ProductDataProvider;

    const ADMIN_PASSWORD = 'admin123';

    private $adminUser;

    private $magentoUrl;

    private $customer;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $this->customer = $this->getCustomer();
        $this->customer->save();

        $this->customerAddress = $this->getCustomerAddress();
        $this->customerAddress->setCustomerId($this->customer->getId());
        $this->customerAddress->save();

        $this->product = $this->getProduct();
        $this->product->save();

        $stock = $this->getProductStock();
        $stock->assignProduct($this->product);
        $stock->save();
    }

    /**
     * @Given a admin user
     * @Then as an Admin user
     */
    public function aAdminUser()
    {
        $this->adminUser = Mage::getModel('admin/user')
            ->setData(
                array(
                    'username'  => mktime() . '_admin',
                    'firstname' => 'Admin',
                    'lastname'  => 'Admin',
                    'email'     => mktime() . '@admin.com',
                    'password'  => self::ADMIN_PASSWORD,
                    'is_active' => 1
                )
            )->save();

        $this->adminUser->setRoleIds(
            array(1)
        )
        ->setRoleUserId($this->adminUser->getUserId())
        ->saveRelations();
    }

    /**
     * @Given a api key
     */
    public function aApiKey()
    {
        $this->apiKey = PAGARME_API_KEY;
    }

    /**
     * @Given a encryption key
     */
    public function aEncryptionKey()
    {
        $this->encryptionKey = PAGARME_ENCRYPTION_KEY;
    }

    /**
     * @When I access the admin
     */
    public function iAccessTheAdmin()
    {
        $session = $this->getSession();
        $session->visit(getenv('MAGENTO_URL') . 'index.php/admin');

        $page = $session->getPage();
        $inputLogin = $page->find('named', array('id', 'username'));
        $inputLogin->setValue($this->adminUser->getUsername());

        $inputPassword = $page->find('named', array('id', 'login'));
        $inputPassword->setValue(self::ADMIN_PASSWORD);

        $page->pressButton('Login');
    }

    /**
     * @When go to system configuration page
     */
    public function goToSystemConfigurationPage()
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $popup = $page->find('css', '.message-popup-head a');

        if ($popup instanceof \Behat\Mink\Element\NodeElement) {
            $popup->click();
        }

        $page->find('named', array('link', 'System'))
            ->mouseOver();

        $page->find('named', array('link', 'Configuration'))
            ->click();

        $page->find('named', array('link', 'Payment Methods'))
            ->click();

        $page->find('css', '#payment_pagarme_settings-head')->click();

        $this->spin(function () use ($page) {
            return $page->findById('config_edit_form') != null;
        }, 10);
    }

    /**
     * @When insert an API key
     */
    public function insertAnApiKey()
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $page->find(
            'named',
            array(
                'id',
                'payment_pagarme_settings_api_key'
            )
        )->setValue($this->apiKey);
    }

    /**
     * @When insert an encryption key
     */
    public function insertAnEncryptionKey()
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $page->find(
            'named',
            array(
                'id',
                'payment_pagarme_settings_encryption_key'
            )
        )->setValue($this->encryptionKey);
    }

    /**
     * @When save configuration
     */
    public function saveConfiguration()
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $page->pressButton('Save Config');
    }

    /**
     * @Then the configuration must be saved with success
     */
    public function theConfigurationMustBeSavedWithSuccess()
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $successMsg = null;

        $this->spin(function () use ($page) {
            return $page->find('css', '.success-msg') != null;
        }, 10);

        $successMsg = $page->find('css', '.success-msg');

        \PHPUnit_Framework_TestCase::assertEquals("The configuration has been saved.", $successMsg->getText());
    }

    public function waitForElement($element, $timeout)
    {
        $this->getSession()->wait(
            $timeout,
            "document.querySelector('${element}').style.display != 'none'"
        );
    }

    public function spin($lambda, $wait)
    {
        for ($i = 0; $i < $wait; $i++) {
            try {
                if ($lambda($this)) {
                    return true;
                }
            } catch (\Exception $e) {
            }

            sleep(1);
        }
    }

    /**
     * @When enable Pagar.me Checkout
     */
    public function enablePagarMeCheckout()
    {
        $page = $this->getSession()->getPage();

        $this->getSession()->wait(5000);
        $select = $page->find(
            'css',
            '#payment_pagarme_settings_active'
        );
        $select->selectOption('Yes');

    }

    /**
     * @Then Pagar.me checkout must be enabled
     */
    public function pagarMeCheckoutMustBeEnabled()
    {
        \PHPUnit_Framework_TestCase::assertTrue(
            Mage::helper('core')->isModuleEnabled('PagarMe_Core')
        );
    }

    /**
     * @When turn on customer data capture
     */
    public function turnOnCustomerDataCapture()
    {
        $captureCustomerData = $this->getSession()->getPage()->find(
            'css',
            '#payment_pagarme_settings_capture_customer_data'
        );

        $captureCustomerData->selectOption('true');
    }

    /**
     * @When change the boleto helper text
     */
    public function changeTheBoletoHelperText()
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_boleto_helper_text',
            'Some info text'
        );
    }

    /**
     * @When change the credit card helper text
     */
    public function changeTheCreditCardHelperText()
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_credit_card_helper_text',
            'Some info text'
        );
    }

    /**
     * @When change the ui color
     */
    public function changeTheUiColor()
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_ui_color',
            '#ff00ff'
        );
    }

    /**
     * @When change the header text
     */
    public function changeTheHeaderText()
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_header_text',
            'Some info text'
        );
    }

    /**
     * @Given Pagar.me settings panel
     */
    public function pagarMeSettingsPanel()
    {
        $this->aAdminUser();
        $this->iAccessTheAdmin();
        $this->goToSystemConfigurationPage();
    }

    /**
     * @When I set interest rate to :interestRate
     */
    public function iSetInterestRateTo($interestRate)
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_interest_rate',
            $interestRate
        );
    }

    /**
     * @When change the payment button text
     */
    public function changeThePaymentButtonText()
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_payment_button_text',
            'Pagar!'
        );
    }
    /**
     * @When I set max instalments to :maxInstallmets
     */
    public function iSetMaxInstalmentsTo($maxInstallmets)
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_max_installments',
            $maxInstallmets
        );
    }

    /**
     * @When change the checkout button text
     */
    public function changeTheCheckoutButtonText()
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_checkout_button_text',
            'Pagar!'
        );
    }

    /**
     * @When I set free instalments to :freeInstallments
     */
    public function iSetFreeInstalmentsTo($freeInstallments)
    {
        $this->getSession()->getPage()->fillField(
            'payment_pagarme_settings_free_installments',
            $freeInstallments
        );
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        $this->adminUser->delete();
        $this->customer->delete();
        $this->product->delete();
    }
}
