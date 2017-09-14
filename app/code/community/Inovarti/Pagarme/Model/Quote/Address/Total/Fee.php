<?php

class Inovarti_Pagarme_Model_Quote_Address_Total_Fee extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    protected $_code = 'fee';

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @return $this|bool
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $this->_setAmount(0);
        $this->_setBaseAmount(0);

        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }

        $quote = $address->getQuote();
        $paymentMethod = $quote->getPayment()->getMethod();

        $baseSubtotalWithDiscount = $quote->getBaseSubtotalWithDiscount();
        $shippingAmount = $quote->getShippingAddress()->getShippingAmount();
        $total = $baseSubtotalWithDiscount + $shippingAmount;

        $post = Mage::app()->getRequest()->getPost();

        $paymentInstallment = 0;
        if ($paymentMethod == 'pagarme_checkout') {
            $paymentInstallment = $post['payment']['pagarme_checkout_installments'] > 1
                ? $post['payment']['pagarme_checkout_installments']
                : $paymentInstallment;
        } elseif ($paymentMethod == 'pagarme_cc') {
            $paymentInstallment = $post['payment']['installments'] > 1
                ? $post['payment']['installments']
                : $paymentInstallment;
        }

        if ($this->mustCalculateInterestForPaymentMethod($paymentMethod)) {
            $installmentConfig = $this->getInstallmentConfig($paymentMethod);
            $interestFeeAmount = $this->getInterestFeeAmount(
                $total,
                $paymentInstallment,
                $installmentConfig
            );

            $address->setFeeAmount($interestFeeAmount);
            $quote->setFeeAmount($interestFeeAmount);
            $quote->setBaseFeeAmount($total);
        }

        $address->setGrandTotal($address->getGrandTotal() + $address->getFeeAmount());
        $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseFeeAmount());

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @return $this
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getFeeAmount();

        if (!$amount) {
            return $this;
        }

        $address->addTotal(array(
            'code' => $this->getCode(),
            'title' => Mage::helper('pagarme')->__('Fee'),
            'value'=> $amount,
        ));

        return $this;
    }

    private function mustCalculateInterestForPaymentMethod($paymentMethod)
    {
        return $paymentMethod == 'pagarme_checkout' || $paymentMethod == 'pagarme_cc';
    }

    public function getInstallmentConfig($paymentMethod)
    {
        if ($paymentMethod == 'pagarme_checkout') {
            return $this->getPagarMeCheckoutInstallmentConfig();
        } elseif ($paymentMethod == 'pagarme_cc') {
            return Mage::getModel('pagarme/cc')->getPagarMeCcInstallmentConfig();
        }
        return null;
    }

    private function getPagarMeCheckoutInstallmentConfig()
    {
        $config = new Varien_Object();
        $config->setMaxInstallments((int) Mage::getStoreConfig('payment/pagarme_checkout/max_installments'));
        $config->setFreeInstallments((int) Mage::getStoreConfig('payment/pagarme_checkout/free_installments'));
        $config->setInterestRate((float) Mage::getStoreConfig('payment/pagarme_checkout/interest_rate'));

        return $config;
    }

    /**
     * @param $collection
     * @param $payment_installment
     * @param $total
     * @param $quote
     * @param $address
     */
    private function getInterestFeeAmount($total, $numberOfInstallments, $installmentConfig)
    {
        $total = Mage::helper('pagarme')->formatAmount($total);
        $creditCard = Mage::getModel('pagarme/cc');

        $interestFeeAmount = $creditCard->calculateInterestFeeAmount($total, $numberOfInstallments, $installmentConfig);

        return $interestFeeAmount;
    }

    /**
     * @param $total
     * @param $minInstallmentValue
     * @param $maxInstallments
     * @return float|int
     */
    private function getMaxInstallments($total, $minInstallmentValue, $maxInstallments)
    {
        $numberInstallments = floor($total / $minInstallmentValue);

        if ($numberInstallments > $maxInstallments) {
            return $maxInstallments;
        }

        return 1;
    }
}
