<?php

class PagarMe_Checkout_Model_Checkout extends Mage_Payment_Model_Method_Abstract
{
    /**
     * @var string
     */
    protected $_code = 'pagarme_checkout';
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
    protected $_formBlockType = 'pagarme_checkout/form_checkout';
    /**
     * @var string
     */
    protected $_infoBlockType = 'pagarme_checkout/info_checkout';

    const PAGARME_CHECKOUT_CREDIT_CARD = 'pagarme_checkout_credit_card';
    const PAGARME_CHECKOUT_BOLETO = 'pagarme_checkout_boleto';

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
            'payment/pagarme_settings/active'
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
            .'_'.$data['pagarme_checkout_payment_method'];

        $additionalInfoData = [
            'pagarme_payment_method' => $paymentMethod,
            'token' => $data['pagarme_checkout_token'],
            'interest_rate' => $data['pagarme_checkout_interest_rate']
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

        $pagarMeSdk = Mage::getModel(
            'pagarme_core/sdk_adapter'
            )->getPagarMeSdk();

        $transaction = $pagarMeSdk->transaction()->get($token);

        try {
            $transaction = $pagarMeSdk->transaction()->capture(
                $transaction,
                $transaction->getAmount()
            );
        } catch (\Exception $exception) {
            throw $exception;
        }

        $order = $payment->getOrder();

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
            'pagarme_transaction_id' => $transaction->getId(),
            'store_order_id'         => $order->getId(),
            'store_increment_id'     => $order->getIncrementId()
        ];

        if ($transaction instanceof PagarMe\Sdk\Transaction\BoletoTransaction) {
            $data['pagarme_boleto_url'] = $transaction->getBoletoUrl();
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
        Mage::getModel('pagarme_core/transaction')
            ->setTransactionId($transaction->getId())
            ->setOrderId($order->getId())
            ->setInstallments($transaction->getInstallments())
            ->setInterestRate(
                $infoInstance->getAdditionalInformation('interest_rate')
            )
            ->setFutureValue(
                Mage::helper('pagarme_core')
                    ->parseAmountToFloat($transaction->getAmount())
            )
            ->save();
    }
}
