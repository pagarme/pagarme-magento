<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class ConfigureContext extends MinkContext
{
    const ADMIN_PASSWORD = 'admin123';

    private $adminUser;

    /**
     * @Given a admin user
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
        $this->apiKey = 'ak_test_xpto';
    }

    /**
     * @Given a enryption key
     */
    public function aEnryptionKey()
    {
        $this->encryptionKey = 'ek_test_xpto';
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

        $page->find('named', array('link', 'System'))
            ->mouseOver();

        $page->find('named', array('link', 'Configuration'))
            ->click();

        $page->find('named', array('link', 'Payment Methods'))
            ->click();

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
                'link',
                'Pagar.me'
            )
        )
        ->click();

        $page->find(
            'named',
            array(
                'id',
                'payment_pagarme_settings_api_key'
            )
        )->setValue('ak_test_');
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
        )->setValue('ek_test_');
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
     * @AfterScenario
     */
    public function tearDown()
    {
        $this->adminUser->delete();
    }
}
