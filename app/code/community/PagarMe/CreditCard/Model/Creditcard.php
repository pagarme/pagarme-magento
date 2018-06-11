<?php
use \PagarMe\Sdk\PagarMe as PagarMeSdk;
use \PagarMe\Sdk\Card\Card as PagarmeCard;
use \PagarMe\Sdk\Transaction\CreditCardTransaction;
use \PagarMe\Sdk\Customer\Customer as PagarmeCustomer;
use \PagarMe\Sdk\ClientException;
use PagarMe_CreditCard_Model_Exception_InvalidInstallments as InvalidInstallmentsException;
use PagarMe_CreditCard_Model_Exception_GenerateCard as GenerateCardException;
use PagarMe_CreditCard_Model_Exception_TransactionsInstallmentsDivergent as TransactionsInstallmentsDivergent;
use PagarMe_CreditCard_Model_Exception_CantCaptureTransaction as CantCaptureTransaction;

class PagarMe_CreditCard_Model_Creditcard extends Mage_Payment_Model_Method_Abstract
{

    use PagarMe_Core_Trait_ConfigurationsAccessor;

    const PAGARME_CREDITCARD = 'pagarme_creditcard';

    protected $_code = 'pagarme_creditcard';
    protected $_formBlockType = 'pagarme_creditcard/form_creditcard';
    protected $_infoBlockType = 'pagarme_creditcard/info_creditcard';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canRefund = true;
    protected $_canUseForMultishipping = true;
    protected $_canManageRecurringProfiles = true;

    /**
     * @var \PagarMe\Sdk\PagarMe
     */
    protected $sdk;

    /**
     * @var PagarMe\Sdk\Transaction\CreditCardTransaction
     */
    protected $transaction;
    protected $pagarmeCoreHelper;
    protected $pagarmeCreditCardHelper;

    /**
     * @var PagarMe_Core_Model_Transaction
     */
    protected $transactionModel;

    const PAGARME_MAX_INSTALLMENTS = 12;

    const AUTHORIZED = 'authorized';
    const PAID = 'paid';

    public function __construct($attributes, PagarMeSdk $sdk = null)
    {
        if (is_null($sdk)) {
            $this->sdk = Mage::getModel('pagarme_core/sdk_adapter')
                 ->getPagarMeSdk();
        }

        $this->pagarmeCoreHelper = Mage::helper('pagarme_core');
        $this->pagarmeCreditCardHelper = Mage::helper('pagarme_creditcard');
        $this->transactionModel = Mage::getModel('pagarme_core/transaction');

        parent::__construct($attributes);
    }

    /**
     * @param \PagarMe\Sdk\PagarMe $sdk
     * @return \PagarMe_CreditCard_Model_Creditcard
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

        return $this->isTransparentCheckoutActiveStoreConfig();
    }

   /**
    * Retrieve payment method title
    *
    * @return string
    */
    public function getTitle()
    {
        return $this->getCreditcardTitleStoreConfig();
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getUrlForPostback()
    {
        $urlForPostback = Mage::getBaseUrl();
        $urlForPostback .=  'pagarme_core/transaction_creditcard/postback';

        return $urlForPostback;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function assignData($data)
    {
        $additionalInfoData = [
            'card_hash' => $data['card_hash'],
            'installments' => $data['installments']
        ];

        $this->getInfoInstance()
            ->setAdditionalInformation($additionalInfoData);

        return $this;
    }

    public function getMaxInstallment()
    {
        return $this->getMaxInstallmentStoreConfig();
    }

    /**
     * Check if installments is between 1 and the defined max installments
     *
     * @param int $installments
     *
     * @throws InvalidInstallmentsException
     *
     * @return void
     */
    public function isInstallmentsValid($installments)
    {
        if ($installments <= 0) {
            $message = $this->pagarmeCoreHelper->__(
                'Installments number should be greater than zero. Was: '
            );
            throw new InvalidInstallmentsException($message . $installments);
        }

        if ($installments > self::PAGARME_MAX_INSTALLMENTS) {
            $message = $this->pagarmeCreditCardHelper->__(
                'Installments number should be lower than Pagar.Me limit'
            );
            throw new InvalidInstallmentsException($message);
        }

        if ($installments > $this->getMaxInstallment()) {
            $message = sprintf(
                Mage::helper('pagarme_creditcard')
                    ->__('Installments number should not be greater than %d'),
                $this->getMaxInstallment()
            );
            $message = $this->pagarmeCoreHelper->__($message);
            throw new InvalidInstallmentsException($message);
        }
    }

    /**
     * @param string $cardHash
     *
     * @return PagarmeCard
     * @throws GenerateCardException
     */
    public function generateCard($cardHash)
    {
        try {
            $card = $this->sdk
                ->card()
                ->createFromHash($cardHash);
            return $card;
        } catch (\Exception $exception) {
            if (getenv('PAGARME_DEVELOPMENT') === 'enabled') {
                return $this->sdk->card()->create(
                    '4242424242424242',
                    'Livia Nascimento',
                    '0224',
                    '123'
                );
            }
            $json = json_decode($exception->getMessage());

            $response = array_reduce($json->errors, function ($carry, $item) {
                return is_null($carry)
                    ? $item->message : $carry."\n".$item->message;
            });

            throw new GenerateCardException($response);
        }
    }

    /**
     * @param int $installments
     * @return void
     * @throws TransactionsInstallmentsDivergent
     */
    public function checkInstallments($installments)
    {
        if ($this->transaction->getInstallments() != $installments) {
            $message = $this->pagarmeCoreHelper->__(
                'Installments is Diverging'
            );
            throw new TransactionsInstallmentsDivergent($message);
        }
    }

    /**
     * Return if a given transaction was paid
     *
     * @return bool
     */
    public function transactionIsPaid()
    {
        if (is_null($this->transaction)) {
            return false;
        }

        if ($this->transaction->getStatus() == self::PAID) {
            return true;
        }

        return false;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    protected function createInvoice($order)
    {
        $invoice = Mage::getModel('sales/service_order', $order)
            ->prepareInvoice();
        
        $invoice->setBaseGrandTotal($order->getGrandTotal());
        $invoice->setGrandTotal($order->getGrandTotal());
        $invoice->setInterestAmount($order->getInterestAmount());
        $invoice->register()->pay();
        $invoice->setTransactionId($this->transaction->getId());

        $order->setState(
            Mage_Sales_Model_Order::STATE_PROCESSING,
            true,
            "pago"
        );

        Mage::getModel('core/resource_transaction')
            ->addObject($order)
            ->addObject($invoice)
            ->save();
    }

    /**
     * @return string
     */
    public function getReferenceKey()
    {
        return $this->transactionModel->getReferenceKey();
    }

    /**
     * @param \PagarMe\Sdk\Card\Card $card
     * @param \PagarMe\Sdk\Customer\Customer $customer
     * @param int $installments
     * @param bool $capture
     * @param string $postbackUrl
     * @return self
     */
    public function createTransaction(
        PagarmeCard $card,
        PagarmeCustomer $customer,
        $installments = 1,
        $capture = false,
        $postbackUrl = null,
        $metadata = [],
        $extraAttributes = []
    ) {
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        $this->transaction = $this->sdk
            ->transaction()
            ->creditCardTransaction(
                $this->pagarmeCoreHelper
                    ->parseAmountToInteger($quote->getGrandTotal()),
                $card,
                $customer,
                $installments,
                $capture,
                $postbackUrl,
                $metadata,
                $extraAttributes
            );

        return $this;
    }

    public function handlePaymentStatus(
        CreditCardTransaction $transaction,
        Varien_Object $payment
    )
    {
        switch ($transaction->getStatus()) {
            case 'pending_review':
            case 'processing':
                $payment->setIsTransactionPending(true);
                break;
        }
        return $payment;
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        $asyncTransaction = $this->getAsyncTransactionConfig();
        $paymentActionConfig = $this->getPaymentActionConfig();
        $captureTransaction = true;
        if($paymentActionConfig === 'authorize_only') {
            $captureTransaction = false;
        }
        $infoInstance = $this->getInfoInstance();
        $order = $payment->getOrder();
        $order->setCapture($paymentActionConfig);
        $referenceKey = $this->getReferenceKey();
        $cardHash = $infoInstance->getAdditionalInformation('card_hash');
        $installments = (int)$infoInstance->getAdditionalInformation(
            'installments'
        );

        $quote = Mage::getSingleton('checkout/session')->getQuote();

        $billingAddress = $quote->getBillingAddress();

        try {
            $this->isInstallmentsValid($installments);
            $card = $this->generateCard($cardHash);

            if ($billingAddress == false) {
                $this->throwBillingException($billingAddress);
                return false;
            }

            $telephone = $billingAddress->getTelephone();

            $customerPagarMe = $this->buildCustomerInformation(
                $quote,
                $billingAddress,
                $telephone
            );

            $postbackUrl = $this->getUrlForPostback();

            $extraAttributes = [
                'async' => (bool)$asyncTransaction,
                'reference_key' => $referenceKey
            ];
            $this->createTransaction(
                $card,
                $customerPagarMe,
                $installments,
                $captureTransaction,
                $postbackUrl,
                [],
                $extraAttributes
            );

            $order->setPagarmeTransaction($this->transaction);
            $this->checkInstallments($installments);

            $payment = $this->handlePaymentStatus($this->transaction, $payment);
        } catch (GenerateCardException $exception) {
            Mage::log($exception->getMessage());
            Mage::logException($exception);
            Mage::throwException($exception->getMessage());
        } catch (InvalidInstallmentsException $exception) {
            Mage::log($exception->getMessage());
            Mage::logException($exception);
            Mage::throwException($exception);
        } catch (TransactionsInstallmentsDivergent $exception) {
            Mage::log($exception->getMessage());
            Mage::logException($exception);
            Mage::throwException($exception);
        } catch (CantCaptureTransaction $exception) {
            Mage::log($exception->getMessage());
            Mage::logException($exception);
        } catch(ClientException $clientException) {
            Mage::log('Client exception');
            if (substr($clientException->getMessage(), 0, 13) === 'cURL error 28') {
                Mage::log('PagarMe API: Operation timed out');
                $order->setData('pending_payment');
                $order->setStatus('peding_payment');
            }
        } catch (\Exception $exception) {
            Mage::log('Exception autorizing:');
            Mage::logException($exception);
            $json = json_decode($exception->getMessage());

            $response = array_reduce($json->errors, function ($carry, $item) {
                return is_null($carry)
                    ? $item->message : $carry."\n".$item->message;
            });

            Mage::throwException($response);
        }

        $this->transactionModel
            ->saveTransactionInformation(
                $order,
                $infoInstance,
                $referenceKey,
                $this->transaction
            );

        return $this;
    }

    public function capture(Varien_Object $payment, $amount)
    {
        $this->transaction = $this->sdk
            ->transaction()
            ->capture($this->transaction);

        if (!$this->transactionIsPaid()) {
            $message = $this->pagarmeCoreHelper->__(
                'Transaction can not be capture'
            );
            throw new CantCaptureTransaction($message);
        }
    }

    private function throwBillingException($billingAddress)
    {
        Mage::log(
            sprintf(
                Mage::helper('pagarme_core')
                    ->__('Undefined Billing address: %s'),
                $billingAddress
            )
        );
    }

    private function buildCustomerInformation($quote, $billingAddress, $telephone)
    {
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

        return $customerPagarMe;
    }

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return $this
     */
    public function refund(Varien_Object $payment, $amount)
    {
        $invoice = $payment->getOrder()
            ->getInvoiceCollection()
            ->getFirstItem();

        if(!$invoice->canRefund()){
            Mage::throwException(
                Mage::helper('pagarme_core')
                    ->__('Invoice can\'t be refunded.')
            );
        }

        $amount = ((float)$invoice->getGrandTotal()) * 100;

        try{

            $this->transaction = $this->sdk
                ->transaction()
                ->get($invoice->getTransactionId());


            $this->sdk
                ->transaction()
                ->creditCardRefund(
                    $this->transaction,
                    $amount
                );

        } catch (\Exception $exception) {
            Mage::log('Exception refund:');
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
}
