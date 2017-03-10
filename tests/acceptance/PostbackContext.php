<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class PostbackContext extends MinkContext
{
    use PagarMe\Magento\Test\Helper\CustomerDataProvider;
    use PagarMe\Magento\Test\Helper\ProductDataProvider;
    use PagarMe\Magento\Test\Helper\PostbackDataProvider;
    use PagarMe\Magento\Test\Helper\PagarMeCheckoutSwitch;

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

        $this->apiKey = PAGARME_API_KEY;

        $this->enablePagarmeCheckout();
    }

    /**
    * @Given a pending boleto order
    */
    public function aPendingBoletoOrder()
    {
        $this->order = $this->getOrder(
            $this->customer,
            $this->customerAddress,
            [
                $this->product
            ]
        );

        $this->order->save();

        \PHPUnit_Framework_TestCase::assertEquals(
            'pending',
            $this->order->getStatus()
        );
    }

    /**
    * @When I receive a postback with status :arg1
    */
    public function iReceiveAPostbackWithStatus($currentStatus)
    {
        $transactionId = Mage::getModel('pagarme_core/service_order')
            ->getTransactionIdByOrder($this->order);

        $algorithm = 'sha1';

        $payload = "id={$transactionId}&current_status={$currentStatus}";

        $hash = hash_hmac($algorithm, $payload, $this->apiKey);

        $signature = "{$algorithm}={$hash}";

        $client = new GuzzleHttp\Client();
        $response = $client->post(
            getenv('MAGENTO_URL') . 'index.php/pagarme/transaction_boleto/postback',
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
    * @Then my order must be updated to :status
    */
    public function myOrderMustBeUpdatedTo($status)
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
        $this->disablePagarmeCheckout();
    }
}
