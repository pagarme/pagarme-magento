<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class PostbackContext extends MinkContext
{
    const MAGENTO_ORDER_STATUS_PENDING = 'pending';

    use PagarMe\Magento\Test\Helper\PagarMeSettings;
    use PagarMe\Magento\Test\Helper\CustomerDataProvider;
    use PagarMe\Magento\Test\Helper\ProductDataProvider;
    use PagarMe\Magento\Test\Helper\PostbackDataProvider;
    use PagarMe\Magento\Test\Helper\PagarMeSwitch;

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

        \Mage::getConfig()
            ->saveConfig('payment/pagarme_configurations/general_api_key', PAGARME_API_KEY);

        \Mage::getConfig()
            ->saveConfig('payment/pagarme_configurations/general_encryption_key', PAGARME_ENCRYPTION_KEY);

        $this->enablePagarmeCheckout();
    }

    /**
    * @Given a pending boleto order
    */
    public function aPendingBoletoOrder()
    {
        $this->order = $this->getOrderPaidByBoleto(
            $this->customer,
            $this->customerAddress,
            [
                $this->product
            ]
        );

        $this->order->save();

        \PHPUnit_Framework_TestCase::assertEquals(
            self::MAGENTO_ORDER_STATUS_PENDING,
            $this->order->getStatus()
        );
    }

    /**
     * @Given a pending credit card order
     */
    public function aPendingCreditCardOrder()
    {
        $this->order = $this->getOrderPaidByCreditCard(
            $this->customer,
            $this->customerAddress,
            [
                $this->product
            ]
        );

        $this->order->save();

        \PHPUnit_Framework_TestCase::assertEquals(
            self::MAGENTO_ORDER_STATUS_PENDING,
            $this->order->getStatus()
        );
    }

    /**
     * @When a :paymentMethod order be paid
     */
    public function aOrderBePaid($paymentMethod)
    {
        $this->processPostbackByPaymentMethod(
            PagarMe_Core_Model_Postback::POSTBACK_STATUS_PAID,
            $paymentMethod
        );
    }

    /**
     * @When the :paymentMethod payment be refunded
     */
    public function thenThePaymentBeRefunded($paymentMethod)
    {
        $this->processPostbackByPaymentMethod(
            PagarMe_Core_Model_Postback::POSTBACK_STATUS_REFUNDED,
            $paymentMethod
        );
    }

    private function processPostbackByPaymentMethod($currentStatus, $paymentMethod)
    {
        $transactionId = Mage::getModel('pagarme_core/service_order')
            ->getTransactionIdByOrder($this->order);

        $algorithm = 'sha1';

        $payload = "id={$transactionId}&current_status={$currentStatus}";

        $apiKey = \Mage::getStoreConfig('payment/pagarme_configurations/general_api_key');

        $hash = hash_hmac($algorithm, $payload, $apiKey);

        $signature = "{$algorithm}={$hash}";

        $urlForPostback = getenv('MAGENTO_URL')
            . 'index.php/pagarme_core/transaction_'
            . $paymentMethod . '/postback';

        $client = new GuzzleHttp\Client();

        $response = $client->post(
            $urlForPostback,
            [
                'headers' => [
                    'X-Hub-Signature' => $signature
                ],
                'body' => [
                    'id' => $transactionId,
                    'current_status' => $currentStatus
                ]
            ]
        );

        \PHPUnit_Framework_TestCase::assertEquals(200, $response->getStatusCode());
        \PHPUnit_Framework_TestCase::assertEquals("ok", (string) $response->getBody());
    }

    /**
    * @Then the order status must be updated to :status
    */
    public function theOrderStatusMustBeUpdatedTo($status)
    {
        $order = Mage::getModel('sales/order')
            ->load($this->order->getId());

        \PHPUnit_Framework_TestCase::assertEquals(
            $status,
            $order->getStatus()
        );
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        $this->restorePagarMeSettings();
    }
}
