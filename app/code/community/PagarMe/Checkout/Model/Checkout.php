<?php

class PagarMe_Checkout_Model_Checkout extends Mage_Payment_Model_Method_Abstract
{
    const PAGARME_CHECKOUT_BOLETO = 'pagarme_checkout_boleto';

    /** @var string */
    protected $_code                   = 'pagarme_checkout';

    /** @var boolean */
    protected $_isGateway              = true;

    /** @var boolean */
    protected $_canAuthorize           = true;

    /** @var boolean */
    protected $_canCapture             = true;

    /** @var boolean */
    protected $_canRefund              = true;

    /** @var boolean */
    protected $_canUseForMultishipping = true;

    /** @var boolean */
    protected $_isInitializeNeeded      = false;

    /** @var string */
    protected $_formBlockType          = 'pagarme_checkout/form_checkout';

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
     * @codeCoverageIgnore
     * @return \PagarMe\Sdk\PagarMe
     */
    public function getPagarMeSdk()
    {
        if (is_null($this->pagarMeSdk)) {
            $this->pagarMeSdk = Mage::getModel('pagarme_core/sdk_adapter')
                ->getPagarMeSdk();
        }

        return $this->pagarMeSdk;
    }

    /**
     * @param \PagarMe\Sdk\PagarMe $pagarMeSdk
     */
    public function setPagarMeSdk(\PagarMe\Sdk\PagarMe $pagarMeSdk)
    {
        $this->pagarMeSdk = $pagarMeSdk;
    }

    /**
     * @param array $data
     *
     * @return PagarMe_Checkout_Model_Checkout
     */
    public function assignData($data)
    {
        $info = $this->getInfoInstance();

        $customerData = Mage::helper('pagarme_core')
            ->prepareCustomerData($data);

        $customer = Mage::helper('pagarme_core')->buildCustomer($customerData);

        $info->setAdditionalInformation(
            [
                'pagarme_payment_method' => $this->_code . '_' . $data['pagarme_checkout_payment_method'],
                'customer' => $customer
            ]
        );

        return $this;
    }

    /**
     * Authorize payment
     *
     * @param Varien_Object
     *
     * @return PagarMe_Checkout_Model_Checkout
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        $infoInstance = $this->getInfoInstance();
        $customer = $infoInstance->getAdditionalInformation('customer');

        $transaction = $this->getPagarMeSdk()
            ->transaction()
            ->boletoTransaction(
                Mage::helper('pagarme_core')->parseAmountToInteger($amount),
                $customer,
                Mage::getUrl('pagarme/transaction_boleto/postback')
            );

        $order = $payment->getOrder();

        $infoInstance->unsAdditionalInformation('customer');
        $infoInstance->setAdditionalInformation(
            array_merge(
                $infoInstance->getAdditionalInformation(),
                [
                    'pagarme_transaction_id' => $transaction->getId(),
                    'pagarme_boleto_url'     => $transaction->getBoletoUrl(),
                    'store_order_id'         => $order->getId(),
                    'store_increment_id'     => $order->getIncrementId()
                ]
            )
        );

        $transaction = Mage::getModel('pagarme_core/transaction')
            ->setTransactionId($transaction->getId())
            ->setOrderId($order->getId())
            ->save();

        return $this;
    }
}
