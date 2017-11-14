<?php

class PagarMe_V2_Checkout_Model_Checkout extends Mage_Payment_Model_Method_Abstract
{
    /**
     * @var string
     */
    protected $_code = 'pagarme_v2_checkout';
    /**
     * @var bool
     */
    protected $_isGateway = true;
    /**
     * @var bool
     */
    protected $_canAuthorize = true;
    /**
     * @var bool
     */
    protected $_canCapture = true;
    /**
     * @var bool
     */
    protected $_canRefund = true;
    /**
     * @var bool
     */
    protected $_canUseForMultishipping = true;
    /**
     * @var bool
     */
    protected $_isInitializeNeeded = false;
    /**
     * @var string
     */
    protected $_formBlockType = 'pagarme_v2_checkout/form_checkout';
    /**
     * @var string
     */
    protected $_infoBlockType = 'pagarme_v2_checkout/info_checkout';

    const PAGARME_CHECKOUT_CREDIT_CARD = 'pagarme_v2_checkout_credit_card';
    const PAGARME_CHECKOUT_BOLETO = 'pagarme_v2_checkout_boleto';

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
            'payment/pagarme_v2_settings/checkout_active'
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
            'payment/pagarme_v2_settings/checkout_title'
        );
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function assignData($data)
    {
        $paymentMethod = $this->_code
            .'_'.$data['pagarme_v2_checkout_payment_method'];

        $additionalInfoData = [
            'pagarme_v2_payment_method' => $paymentMethod,
            'token' => $data['pagarme_v2_checkout_token'],
            'interest_rate' => $data['pagarme_v2_checkout_interest_rate']
        ];

        $this->getInfoInstance()
            ->setAdditionalInformation($additionalInfoData);

        return $this;
    }

    /**
     * Authorize payment
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @throws Exception
     *
     * @return $this
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        $infoInstance = $this->getInfoInstance();

        $token = $infoInstance->getAdditionalInformation('token');

        $infoInstance->unsAdditionalInformation('token');

        if (empty($token)) {
            throw new \Exception(
                Mage::helper('pagarme_v2_checkout')->__(
                    'Error, please review your payment info'
                ),
                1
            );
        }

        $pagarMeSdk = Mage::getModel('pagarme_v2_core/sdk_adapter')
            ->getPagarMeSdk();

        $transaction = $pagarMeSdk->transaction()->get($token);

        $order = $payment->getOrder();

        try {
            $transaction = $pagarMeSdk->transaction()->capture(
                $transaction,
                $transaction->getAmount(),
                ['order_id' => $order->getIncrementId()]
            );
        } catch (\Exception $exception) {
            \Mage::logException($exception->getMessage());

            throw $exception;
        }

        $subTotal = $payment->getOrder()->getSubtotal();
        $subtotalWithDiscount = $payment->getOrder()->getQuote()->getSubtotalWithDiscount();
        $discount = $subTotal - $subtotalWithDiscount;

        $order->setDiscountAmount($discount);
        $order->setGrandTotal($transaction->getAmount()/100);

        $infoInstance->setAdditionalInformation(
            $this->extractAdditionalInfo($infoInstance, $transaction, $order)
        );

        $this->saveTransactionInformation($order, $transaction, $infoInstance);

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
            'pagarme_v2_transaction_id' => $transaction->getId(),
            'store_order_id'         => $order->getId(),
            'store_increment_id'     => $order->getIncrementId()
        ];

        if ($transaction instanceof PagarMe\Sdk\Transaction\BoletoTransaction) {
            $data['pagarme_v2_boleto_url'] = $transaction->getBoletoUrl();
        }

        return array_merge(
            $infoInstance->getAdditionalInformation(),
            $data
        );
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param PagarMe\Sdk\Transaction\AbstractTransaction $transaction
     * @param Mage_Sales_Model_Order_Payment $infoInstance
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    private function saveTransactionInformation(
        Mage_Sales_Model_Order $order,
        PagarMe\Sdk\Transaction\AbstractTransaction $transaction,
        $infoInstance
    ) {
        $installments = 1;
        $rateAmount = 0;
        $interestRate = 0;
        $totalAmount = Mage::helper('pagarme_v2_core')
            ->parseAmountToFloat($transaction->getAmount());

        if ($transaction instanceof PagarMe\Sdk\Transaction\CreditCardTransaction) {
            $installments = $transaction->getInstallments();

            $rateAmount = ($totalAmount - $order->getBaseGrandTotal());
            $interestRate = $infoInstance->getAdditionalInformation('interest_rate');
        }

        Mage::getModel('pagarme_v2_core/transaction')
            ->setTransactionId($transaction->getId())
            ->setOrderId($order->getId())
            ->setInstallments($installments)
            ->setInterestRate($interestRate)
            ->setPaymentMethod($transaction::PAYMENT_METHOD)
            ->setFutureValue($totalAmount)
            ->setRateAmount($rateAmount)
            ->save();

        $order->setGrandTotal($totalAmount);
        $order->save();
    }
}
