<?php
namespace PagarMe\Magento\Test;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use PagarMe\Magento\Test\Helper\AdminAccessProvider;
use PagarMe\Magento\Test\Helper\SessionWait;
use PagarMe\Magento\Test\Helper\CustomerDataProvider;
use PagarMe\Magento\Test\Helper\ProductDataProvider;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

abstract class PagarMeMagentoContext extends RawMinkContext
{
    use CustomerDataProvider;
    use ProductDataProvider;

    /**
     * Used during admin login
     */
    const ADMIN_PASSWORD = 'magentorocks1';

    /**
     * @var Behat\Mink\Session
     */
    protected $session;

    /**
     * @var string
     */
    protected $magentoUrl;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $this->session = $this->getSession();
        $this->magentoUrl = getenv('MAGENTO_URL');
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
     * @Given a admin user
     * @Then as an Admin user
     */
    public function aAdminUser()
    {
        $this->adminUser = \Mage::getModel('admin/user')
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
     * @When I access the admin
     */
    public function iAccessTheAdmin()
    {
        $this->session->visit($this->magentoUrl . 'index.php/admin');

        $page = $this->session->getPage();
        $inputLogin = $page->find('named', array('id', 'username'));
        $inputLogin->setValue($this->adminUser->getUsername());

        $inputPassword = $page->find('named', array('id', 'login'));
        $inputPassword->setValue(self::ADMIN_PASSWORD);

        $page->pressButton('Login');
    }
}