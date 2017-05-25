<?php

class PagarMe_Checkout_Block_Info_Checkout extends Mage_Payment_Block_Info
{
    /**
     * @var \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    protected $transaction;

    const PAYMENT_METHOD_CREDIT_CARD_LABEL = 'Cartão de Crédito';
    const PAYMENT_METHOD_BOLETO_LABEL = 'Boleto';

    public function _construct()
    {
        parent::_construct();

        if (Mage::app()->getStore()->isAdmin()) {
            $this->setTemplate(
                'pagarme/checkout/order_info/payment_details.phtml'
            );
        }
    }

    /**
     * @codeCoverageIgnore
     *
     * @return PagarMe_Core_Model_Transaction
     */
    public function getTransaction()
    {
        $order = $this->getInfo()->getOrder();

        if (is_null($this->transaction) && !is_null($order)) {
            $this->transaction = \Mage::getModel('pagarme_core/service_order')
                ->getTransactionByOrderId(
                    $order->getId()
                );

            return $this->transaction;
        }

        $additionalInformation = $this->getInfo()->getAdditionalInformation();

        if (is_array($additionalInformation)
            && isset($additionalInformation['token'])
        ) {
            $this->transaction = \Mage::getModel('pagarme_core/sdk_adapter')
                ->getPagarMeSdk()
                ->transaction()
                ->get($additionalInformation['token']);

            return $this->transaction;
        }

        throw new \Exception('Transaction was not found.');
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->transaction->getPaymentMethod()
            == \PagarMe\Sdk\Transaction\CreditCardTransaction::PAYMENT_METHOD
            ? self::PAYMENT_METHOD_CREDIT_CARD_LABEL
            : self::PAYMENT_METHOD_BOLETO_LABEL;
    }

    /**
     * @return Varien_Object
     */
    protected function _prepareSpecificInformation()
    {
        $specificInformation = [];

        $transaction = $this->getTransaction();

        if (!is_null($transaction)) {
            $installments = $transaction->getInstallments();
            if (is_null($installments)) {
                $installments = 1;
            }

            $additionalInformation = $this->getInfo()
                ->getAdditionalInformation();

            $specificInformation = array_merge($specificInformation, [
                'Payment Method' => $this->getPaymentMethod(),
                'Installments' => $installments
            ]);

            if ($this->getPaymentMethod() === self::PAYMENT_METHOD_CREDIT_CARD_LABEL
                && $additionalInformation['interest_rate'] > 0
            ) {
                $specificInformation['Interest Fee %'] = $additionalInformation['interest_rate'];
            }
        }

        return new Varien_Object($specificInformation);
    }
}
