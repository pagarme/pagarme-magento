<?php
use \PagarMe\Sdk\PagarMe as PagarMeSdk;

class PagarMe_Boleto_Model_Boleto extends PagarMe_Core_Model_AbstractPaymentMethod
{
    protected $_code = 'pagarme_boleto';
    protected $_formBlockType = 'pagarme_boleto/form_boleto';
    protected $_infoBlockType = 'pagarme_boleto/info_boleto';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canRefund = true;
    protected $_canUseForMultishipping = true;
    protected $_canManageRecurringProfiles = true;
    protected $_isInitializeNeeded = true;

    const PAGARME_BOLETO = 'pagarme_boleto';
    const POSTBACK_ENDPOINT = 'transaction_boleto';

    /**
     * @var \PagarMe\Sdk\PagarMe
     */
    protected $sdk;

    /**
     * @var PagarMe\Sdk\Transaction\BoletoTransaction
     */
    protected $transaction;
    protected $pagarmeCoreHelper;

    public function __construct($attributes, PagarMeSdk $sdk = null)
    {
        if (is_null($sdk)) {
            $this->sdk = Mage::getModel('pagarme_core/sdk_adapter')
                 ->getPagarMeSdk();
        }

        $this->pagarmeCoreHelper = Mage::helper('pagarme_core');
        parent::__construct($attributes);
    }

    /**
     * Method that will be executed instead of magento's authorize default
     * workflow
     *
     * @param string $paymentAction
     * @param Varien_Object $stateObject
     *
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function initialize($paymentAction, $stateObject)
    {
        $this->stateObject = $stateObject;

        $payment = $this->getInfoInstance();

        $this->stateObject->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
        $this->stateObject->setStatus(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
        $this->stateObject->setIsNotified(true);

        $this->authorize(
            $payment,
            $payment->getOrder()->getBaseTotalDue()
        );

        $payment->setAmountAuthorized(
            $payment->getOrder()->getTotalDue()
        );

        return $this;
    }

    /**
     * @param type $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if (!parent::isAvailable($quote)) {
            return false;
        }

        return (bool) Mage::getStoreConfig(
            'payment/pagarme_configurations/transparent_active'
        );
    }

   /**
    * Retrieve payment method title
    *
    * @return string
    */
    public function getTitle()
    {
        return Mage::getStoreConfig(
            'payment/pagarme_configurations/boleto_title'
        );
    }

    /**
     * @return string
     */
    protected function getPostbackCode()
    {
        return self::POSTBACK_ENDPOINT;
    }

    /**
     * @param \PagarMe\Sdk\Customer\Customer $customer
     * @return self
     */
    public function createTransaction(
        \PagarMe\Sdk\Customer\Customer $customer
    ) {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $this->transaction = $this->sdk
            ->transaction()
            ->boletoTransaction(
                $this->pagarmeCoreHelper
                    ->parseAmountToInteger($quote->getGrandTotal()),
                $customer,
                $postBackURL,
                $payment->getOrder()
            );

        return $this;
    }
    
    /**
     * @param array $data
     *
     * @return $this
     */
    public function assignData($data)
    {
        $additionalInfoData = [
            'pagarme_payment_method' => self::PAGARME_BOLETO
        ];

        $this->getInfoInstance()
            ->setAdditionalInformation($additionalInfoData);

        return $this;
    }

    /**
     * Given a boleto, set its related order status as pending_payment
     *
     * @param int $amount
     * @param Mage_Sales_Model_Order $order
     */
    private function setOrderAsPendingPayment($amount, $order)
    {
        $message = 'Boleto is waiting payment';
        $notifyCustomer = true;
        $order->setState(
            Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,
            Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,
            $this->pagarmeCoreHelper->__($message, $amount),
            $notifyCustomer
        );
    }

    /**
     * @return string
     */
    public function getReferenceKey()
    {
        return Mage::getModel('pagarme_core/transaction')
            ->getReferenceKey();
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     *
     * @throws Mage_Core_Exception
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        try {
            $infoInstance = $this->getInfoInstance();
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            if (Mage::getSingleton('admin/session')->isLoggedIn()) {
                $session = Mage::getSingleton('adminhtml/session_quote');
                $quote = $session->getQuote();
            }
            $billingAddress = $quote->getBillingAddress();
            $referenceKey = $this->getReferenceKey();

            if ($billingAddress == false) {
                Mage::log(
                    sprintf(
                        'Undefined Billing address: %s',
                        $billingAddress
                    )
                );
                return false;
            }
            $telephone = $billingAddress->getTelephone();
            $customer = $this->pagarmeCoreHelper->prepareCustomerData([
                'pagarme_modal_customer_document_number' => $quote->getCustomerTaxvat(),
                'pagarme_modal_customer_document_type' => $this->pagarmeCoreHelper->getDocumentType($quote),
                'pagarme_modal_customer_name' => $this->pagarmeCoreHelper->getCustomerNameFromQuote($quote),
                'pagarme_modal_customer_email' => $quote->getCustomerEmail(),
                'pagarme_modal_customer_born_at' => $quote->getDob(),
                'pagarme_modal_customer_address_street_1' => $billingAddress->getStreet(1),
                'pagarme_modal_customer_address_street_2' => $billingAddress->getStreet(2),
                'pagarme_modal_customer_address_street_3' => $billingAddress->getStreet(3),
                'pagarme_modal_customer_address_street_4' => $billingAddress->getStreet(4),
                'pagarme_modal_customer_address_city' => $billingAddress->getCity(),
                'pagarme_modal_customer_address_state' => $billingAddress->getRegion(),
                'pagarme_modal_customer_address_zipcode' => $billingAddress->getPostcode(),
                'pagarme_modal_customer_address_country' => $billingAddress->getCountry(),
                'pagarme_modal_customer_phone_ddd' => $this->pagarmeCoreHelper->getDddFromPhoneNumber($telephone),
                'pagarme_modal_customer_phone_number' => $this->pagarmeCoreHelper->getPhoneWithoutDdd($telephone),
                'pagarme_modal_customer_gender' => $quote->getGender()
            ]);
            $customerPagarMe = $this->pagarmeCoreHelper
                ->buildCustomer($customer);
            $order = $payment->getOrder();
            $extraAttributes = [
                'async' => false,
                'reference_key' => $referenceKey
            ];

            $amount = $this->pagarmeCoreHelper
                ->parseAmountToInteger($quote->getGrandTotal());

            $this->transaction = $this->sdk
                ->transaction()
                ->boletoTransaction(
                    $amount,
                    $customerPagarMe,
                    $this->getUrlForPostback(),
                    ['order_id' => $order->getIncrementId()],
                    $extraAttributes
                );

            $this->setOrderAsPendingPayment($amount, $order);

            $infoInstance->setAdditionalInformation(
                $this->extractAdditionalInfo($infoInstance, $this->transaction, $order)
            );
            Mage::getModel('pagarme_core/transaction')
                ->saveTransactionInformation(
                    $order,
                    $infoInstance,
                    $referenceKey,
                    $this->transaction
                );
        } catch (\Exception $exception) {
            Mage::logException($exception);
            $json = json_decode($exception->getMessage());

            $response = array_reduce($json->errors, function ($carry, $item) {
                return is_null($carry)
                    ? $item->message : $carry."\n".$item->message;
            });
            Mage::throwException($response);
        }

        return $this;
    }
    
    /**
     * @param Mage_Sales_Model_Order_Payment $infoInstance
     * @param \PagarMe\Sdk\Transaction\AbstractTransaction $transaction
     * @param Mage_Sales_Model_Order $order
     *
     * @return array
     */
    private function extractAdditionalInfo($infoInstance, $transaction, $order)
    {
        $data = [
            'pagarme_transaction_id' => $transaction->getId(),
            'store_order_id' => $order->getId(),
            'store_increment_id' => $order->getIncrementId(),
            'pagarme_boleto_url' => $transaction->getBoletoUrl(),
        ];
        
        return array_merge(
            $infoInstance->getAdditionalInformation(),
            $data
        );
    }
}
