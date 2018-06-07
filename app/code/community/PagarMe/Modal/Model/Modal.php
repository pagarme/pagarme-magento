<?php

class PagarMe_Modal_Model_Modal extends Mage_Payment_Model_Method_Abstract
{
    /**
     * @var string
     */
    protected $_code = 'pagarme_modal';
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
    protected $_formBlockType = 'pagarme_modal/form_modal';
    /**
     * @var string
     */
    protected $_infoBlockType = 'pagarme_modal/info_modal';

    const PAGARME_MODAL_CREDIT_CARD = 'pagarme_modal_credit_card';
    const PAGARME_MODAL_BOLETO = 'pagarme_modal_boleto';
    const PAGARME_MODAL = 'pagarme_modal';

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
            'payment/pagarme_configurations/modal_active'
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
            'payment/pagarme_configurations/modal_title'
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
            .'_'.$data['pagarme_modal_payment_method'];

        $additionalInfoData = [
            'pagarme_payment_method' => $paymentMethod,
            'token' => $data['pagarme_modal_token'],
            'interest_rate' => $data['pagarme_modal_interest_rate']
        ];

        $this->getInfoInstance()
            ->setAdditionalInformation($additionalInfoData);

        return $this;
    }

    /**
     * @return string
     */
    public function getReferenceKey()
    {
        return \Mage::getModel('pagarme_core/transaction')
            ->getReferenceKey();
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
                Mage::helper('pagarme_modal')->__(
                    'Error, please review your payment info'
                ),
                1
            );
        }

        $pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter')
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
            \Mage::log($exception->getMessage());
            \Mage::logException($exception);

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
        $referenceKey = $this->getReferenceKey();
        Mage::getModel('pagarme_core/transaction')
            ->saveTransactionInformation(
                $order,
                $infoInstance,
                $referenceKey,
                $transaction
            );

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
    
}
