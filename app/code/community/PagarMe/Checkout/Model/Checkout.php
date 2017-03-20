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

    const PAGARME_CHECKOUT_CREDIT_CARD = 'pagarme_checkout_credit_card';
    const PAGARME_CHECKOUT_BOLETO = 'pagarme_checkout_boleto';

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
     *
     * @codeCoverageIgnore
     */
    public function assignData($data)
    {
        $paymentMethod = $this->_code . '_' . $data['pagarme_checkout_payment_method'];
        $token = $data['pagarme_checkout_token'];

        $additionalInfoData = [
            'pagarme_payment_method' => $paymentMethod,
            'token' => $token
        ];

        $this->getInfoInstance()->setAdditionalInformation($additionalInfoData);

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
     *
     * @codeCoverageIgnore
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        $infoInstance = $this->getInfoInstance();

        $preTransaction = Mage::getModel('pagarme_core/entity_PaymentMethodFactory')
            ->createTransactionObject(
                $amount,
                $infoInstance
            );

        $infoInstance->unsAdditionalInformation('token');

        try {
            $transaction = Mage::getModel('pagarme_core/service_transaction')
                ->capture($preTransaction);
        } catch (\Exception $exception) {
            throw $exception;
        }

        $order = $payment->getOrder();

        $infoInstance->setAdditionalInformation(
            $this->extractAdditionalInfo($infoInstance, $transaction, $order)
        );

        Mage::getModel('pagarme_core/transaction')
            ->setTransactionId($transaction->getId())
            ->setOrderId($order->getId())
            ->save();

        return $this;
    }

    /**
     * @param type $infoInstance
     * @param \PagarMe\Sdk\Transaction\AbstractTransaction $transaction
     * @param type $order
     *
     * @return array
     *
     * @codeCoverageIgnore
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
}
