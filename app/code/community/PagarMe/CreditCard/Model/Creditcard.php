<?php
use \PagarMe\Sdk\PagarMe as PagarMeSdk;
use \PagarMe\Sdk\Card\Card as PagarmeCard;
use \PagarMe\Sdk\Transaction\AbstractTransaction;
use \PagarMe\Sdk\Transaction\CreditCardTransaction;
use \PagarMe\Sdk\Customer\Customer as PagarmeCustomer;
use \PagarMe\Sdk\PagarMeException;
use PagarMe_CreditCard_Model_Exception_InvalidInstallments as InvalidInstallmentsException;
use PagarMe_CreditCard_Model_Exception_GenerateCard as GenerateCardException;
use PagarMe_CreditCard_Model_Exception_TransactionsInstallmentsDivergent as TransactionsInstallmentsDivergent;
use PagarMe_CreditCard_Model_Exception_CantCaptureTransaction as CantCaptureTransaction;
use PagarMe_Core_Model_System_Config_Source_PaymentAction as PaymentActionConfig;

class PagarMe_CreditCard_Model_Creditcard extends PagarMe_Core_Model_AbstractPaymentMethod
{

    use PagarMe_Core_Trait_ConfigurationsAccessor;

    const PAGARME_CREDITCARD = 'pagarme_creditcard';

    protected $_code = 'pagarme_creditcard';
    protected $_formBlockType = 'pagarme_creditcard/form_creditcard';
    protected $_infoBlockType = 'pagarme_creditcard/info_creditcard';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = true;
    protected $_canRefund = true;
    protected $_canUseForMultishipping = true;
    protected $_canManageRecurringProfiles = true;
    protected $_isInitializeNeeded = true;

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
     * @var Mage_Sales_Model_Quote
     */
    protected $quote;

    /**
     * @var PagarMe_Core_Model_Transaction
     */
    protected $transactionModel;

    /**
     * @var \Varien_Object
     */
    private $stateObject;

    const PAGARME_MAX_INSTALLMENTS = 12;
    const POSTBACK_ENDPOINT = 'transaction_creditcard';

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
     * Method that will be executed instead of magento's default workflow
     * (authorize or capture)
     *
     * @param string $paymentAction
     * @param Varien_Object $stateObject
     *
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function initialize($paymentAction, $stateObject)
    {
        $this->stateObject = $stateObject;

        $paymentActionConfig = $this->getPaymentActionConfig();
        $asyncTransactionConfig = (bool) $this->getAsyncTransactionConfig();
        $payment = $this->getInfoInstance();

        $this->stateObject->setState(Mage_Sales_Model_Order::STATE_PROCESSING);
        $this->stateObject->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
        $this->stateObject->setIsNotified(true);

        if (
            $paymentActionConfig === PaymentActionConfig::AUTH_ONLY ||
            $asyncTransactionConfig === true
        ) {
            $stateObject->setState(
                Mage_Sales_Model_Order::STATE_PENDING_PAYMENT
            );
            $stateObject->setStatus(
                Mage_Sales_Model_Order::STATE_PENDING_PAYMENT
            );
        }

        if (
            $paymentAction ===
            Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE
        ) {
            $this->authorize(
                $payment,
                $payment->getOrder()->getBaseTotalDue()
            );
            $payment->setAmountAuthorized(
                $payment->getOrder()->getTotalDue()
            );
        }
        return $this;
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

    public function setQuote(Mage_Sales_Model_Quote $quote)
    {
        $this->quote = $quote;
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
     * @return string
     */
    protected function getPostbackCode()
    {
        return self::POSTBACK_ENDPOINT;
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
        $info = $this->getInfoInstance();
        $info->setAdditionalInformation($additionalInfoData);

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

    private function getTestCard()
    {
        $card = null;
        if ($this->isDeveloperModeEnabled()) {
            $card = $this->sdk->card()->create(
                '4242424242424242',
                'Livia Nascimento',
                '0224',
                '123'
            );
        }

        return $card;
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
            $card = $this->getTestCard();
            if ($card instanceof \PagarMe\Sdk\Card\Card) {
                return $card;
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
     * @param \PagarMe\Sdk\PagarMeException $exception
     * @return string
     */
    private function formatPagarmeExceptions($exception)
    {
        $json = json_decode($exception->getMessage());
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $exception->getMessage();
        }

        return array_reduce($json->errors, function ($carry, $item) {
            return is_null($carry)
                ? $item->message : $carry."\n".$item->message;
        });
    }

    /**
     * Add to payment card informations provided from API
     *
     * @param \Mage_Sales_Model_Order_Payment $payment
     * @param \PagarMe\Sdk\Card\Card $card
     *
     * @return \Mage_Sales_Model_Order_Payment
     */
    public function insertCardInfosOnPayment($payment, $card)
    {
        $payment
            ->setCcType($card->getBrand())
            ->setCcOwner($card->getHolderName())
            ->setCcLast4($card->getLastDigits());

        return $payment;
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
        $this->transaction = $this->sdk
            ->transaction()
            ->creditCardTransaction(
                $this->pagarmeCoreHelper
                    ->parseAmountToInteger($this->quote->getGrandTotal()),
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

    private function buildRefusedReasonMessage()
    {
        $refusedMessage = 'Unauthorized';

        $refusedReason = $this->transaction->getRefuseReason();

        if ($refusedReason === self::REFUSE_REASON_ANTIFRAUD) {
            $refusedMessage = 'Suspected fraud';
        }

        return $refusedMessage;
    }

    /**
     * @return string
     */
    private function buildCheckoutRefusedMessage()
    {
        $defaultMessage = $this->pagarmeCreditCardHelper
            ->__('Payment refused.');
        $contactMessage = $this->pagarmeCreditCardHelper
            ->__('Please, contact your bank for more informations.');

        if ($this->transaction->getRefuseReason() === 'antifraud') {
            $contactMessage = $this->pagarmeCreditCardHelper
                ->__('Please, contact us for more informations.');
        }

        return sprintf(
            "%s\n%s",
            $defaultMessage,
            $contactMessage
        );
    }

    /**
     * @param \Mage_Sales_Model_Order_Payment $payment
     *
     * @return \Varien_Object
     * @throws Mage_Payment_Model_Info_Exception
     */
    private function handlePaymentStatus(
        Mage_Sales_Model_Order_Payment $payment
    ) {
        $order = $payment->getOrder();
        $notifyCustomer = false;
        $amount = Mage::helper('core')->currency(
            $order->getGrandTotal(),
            true,
            false
        );

        switch ($this->transaction->getStatus()) {
            case AbstractTransaction::PROCESSING:
                $message = 'Processing on Gateway. Waiting response';
                $desiredStatus = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;

                $order->setState(
                    $desiredStatus,
                    $desiredStatus,
                    $this->pagarmeCoreHelper->__($message, $amount),
                    $notifyCustomer
                );
                break;
            case AbstractTransaction::REFUSED:
                throw new Mage_Payment_Model_Info_Exception(
                    $this->buildCheckoutRefusedMessage()
                );
                break;
            case AbstractTransaction::PENDING_REVIEW:
                $message = 'Waiting transaction review on Pagar.me\'s Dashboard';
                $desiredStatus = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;

                $order->setState(
                    $desiredStatus,
                    $desiredStatus,
                    $this->pagarmeCoreHelper->__($message, $amount),
                    $notifyCustomer
                );
                break;
            case AbstractTransaction::AUTHORIZED:
                $message = 'Authorized amount of %s';
                $desiredStatus = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
                $notifyCustomer = true;

                $order->setState(
                    $desiredStatus,
                    $desiredStatus,
                    $this->pagarmeCoreHelper->__($message, $amount),
                    $notifyCustomer
                );
                break;
            case AbstractTransaction::PAID:
                $message = 'Captured amount of %s';
                $desiredStatus = Mage_Sales_Model_Order::STATE_PROCESSING;
                $notifyCustomer = true;

                $order->setState(
                    $desiredStatus,
                    $desiredStatus,
                    $this->pagarmeCoreHelper->__($message, $amount),
                    $notifyCustomer
                );
                break;
        }

        return $payment;
    }

    /**
     * Defines additional information from transaction
     *
     * @param Mage_Sales_Model_Order_Payment $infoInstance $infoInstance
     * @param CreditCardTransaction $transaction
     *
     * @return array
     */
    private function getPaymentAdditionalInformation(
        $infoInstance,
        $transaction
    ) {
        return array_merge(
            $infoInstance->getAdditionalInformation(),
            [
                'pagarme_transaction_id' => $transaction->getId(),
            ]
        );
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        $asyncTransaction = $this->getAsyncTransactionConfig();
        $paymentActionConfig = $this->getPaymentActionConfig();
        $captureTransaction = true;
        if ($paymentActionConfig === PaymentActionConfig::AUTH_ONLY) {
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

        $this->quote = $order->getQuote();
        $billingAddress = $this->quote->getBillingAddress();

        try {
            $this->isInstallmentsValid($installments);
            $card = $this->generateCard($cardHash);

            if ($billingAddress == false) {
                $this->throwBillingException($billingAddress);
                return false;
            }

            $telephone = $billingAddress->getTelephone();

            $customerPagarMe = $this->buildCustomerInformation(
                $this->quote,
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
                ['order_id' => $order->getIncrementId()],
                $extraAttributes
            );

            $order->setPagarmeTransaction($this->transaction);
            $this->checkInstallments($installments);

            if ($this->transaction->isPaid()) {
                $this->createInvoice($order);
            }

            $payment = $this->handlePaymentStatus($payment);
            $payment = $this->insertCardInfosOnPayment(
                $payment,
                $this->transaction->getCard()
            );

            $paymentAdditionalInfo = $this->getPaymentAdditionalInformation(
                $infoInstance,
                $this->transaction
            );
            $infoInstance->setAdditionalInformation($paymentAdditionalInfo);
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
        } catch (PagarMeException $pagarMeException) {
            if (substr($pagarMeException->getMessage(), 0, 13) === 'cURL error 28') {
                $timeoutMessage = sprintf(
                    'PagarMe API: Operation timed out for order %s',
                    $order->getId()
                );
                Mage::log($timeoutMessage);
                $payment->setIsTransactionPending(true);
            } else {
                Mage::throwException(
                    $this->formatPagarmeExceptions($pagarMeException)
                );
            }
        } catch (Mage_Payment_Model_Info_Exception $refusedException) {
            Mage::throwException($refusedException->getMessage());
        } catch (\Exception $exception) {
            Mage::logException($exception);

            Mage::throwException($exception);
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

    /**
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return void
     *
     * @throws \Exception
     */
    public function capture(Varien_Object $payment, $amount)
    {
        $order = $payment->getOrder();
        $integerAmount = Mage::helper('pagarme_core')
            ->parseAmountToInteger($amount);

        $transactionId = Mage::getModel(
            'pagarme_core/service_order'
        )->getTransactionIdByOrder($order);

        $transactionModel = Mage::getModel(
            'pagarme_core/service_transaction'
        );

        try {
            $this->transaction = $transactionModel->getTransactionById(
                $transactionId
            );

            $this->transaction = $this->sdk
                ->transaction()
                ->capture(
                    $this->transaction,
                    $integerAmount
                );

            return $this;
        } catch (\Exception $exception) {
            throw $exception;
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
     * @param float $amount
     * @throws \Exception
     * @return $this
     */
    public function refund(Varien_Object $payment, $amount)
    {
        $invoice = $payment->getOrder()
            ->getInvoiceCollection()
            ->getFirstItem();

        if (!$invoice->canRefund()) {
            Mage::throwException(
                Mage::helper('pagarme_core')
                    ->__('Invoice can\'t be refunded.')
            );
        }

        $amount = ((float)$invoice->getGrandTotal()) * 100;

        try {
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
