<?php
use \PagarMe\Sdk\PagarMe as PagarMeSdk;

class PagarMe_Boleto_Model_Boleto extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'pagarme_boleto';
    protected $_formBlockType = 'pagarme_boleto/form_boleto';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canRefund = true;
    protected $_canUseForMultishipping = true;
    protected $_canManageRecurringProfiles = true;
 
    const PAGARME_BOLETO = 'pagarme_boleto';

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
     * @param \PagarMe\Sdk\PagarMe $sdk
     * @return \PagarMe_Boleto_Model_Boleto
     *
     * @codeCoverageIgnore
     */
    public function setSdk(PagarMeSdk $sdk)
    {
        $this->sdk = $sdk;

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
    
    public function authorize(Varien_Object $payment, $amount)
    {
        try {
           
            $infoInstance = $this->getInfoInstance();
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $billingAddress = $quote->getBillingAddress();
            if ($billingAddress == false) {
                Mage::logException(
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
            $this->transaction = $this->sdk
                ->transaction()
                ->boletoTransaction(
                    $this->pagarmeCoreHelper
                    ->parseAmountToInteger($quote->getGrandTotal()),
                        $customerPagarMe,
                        Mage::getBaseUrl() . 'pagarme_core/transaction_boleto/postback',
                        ['order_id' => $order->getIncrementId()],
                        ['async' => false]
                    );

            $infoInstance->setAdditionalInformation(
                $this->extractAdditionalInfo($infoInstance, $this->transaction, $order)
            );
            
            Mage::getModel('pagarme_core/transaction')
                ->saveTransactionInformation(
                    $order,
                    $this->transaction,
                    $infoInstance
                );
        } catch (\Exception $exception) {
            $json = json_decode($exception->getMessage());
            $json = json_decode($json);
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
