<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 *
 * UPDATED:
 *
 * @copyright   Copyright (C) 2015 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */
class Inovarti_Pagarme_Block_Form_Cc extends Mage_Payment_Block_Form_Cc
{
    const PAYMENT_METHOD_TYPE = 'pagarme_cc';

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('pagarme/form/cc.phtml');
    }

    public function getCcMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months)) {
            $months[0] =  $this->__('Month');
            for ($i=1; $i <= 12; $i++) {
                $months[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
            }
            $this->setData('cc_months', $months);
        }
        return $months;
    }

    public function getCheckoutQuote()
    {
        return Mage::helper('checkout')->getQuote();
    }

    public function getPagarmeAPI()
    {
        return Mage::getModel('pagarme/api');
    }

    public function getSalesRuleCollection()
    {
        return Mage::getResourceModel('salesrule/rule_collection')->load();
    }

    public function hasCreditCardDiscountRules()
    {
        $ruleCollection = $this->getSalesRuleCollection();
        $checkoutPaymentMethod = $this->getCheckoutQuote()->getPayment()->getMethod();

        foreach ($ruleCollection as $rule) {
            $ruleIsValid = $rule->validate(Mage::helper('checkout'));
            $isPaymentMethodEqual = ($checkoutPaymentMethod == self::PAYMENT_METHOD_TYPE);
            if ($ruleIsValid && $isPaymentMethodEqual) {
                return true;
            }  
        } 

        return false;
    }

    public function getCheckoutTotalAmount()
    {
        $hasDiscountRules = $this->hasCreditCardDiscountRules();
        $checkoutQuote = $this->getCheckoutQuote();
        $totalAmount = $checkoutQuote->getBaseSubtotal();

        if ($hasDiscountRules) {
            $totalAmount = $checkoutQuote->getBaseSubtotalWithDiscount();
        }

        return $totalAmount + $checkoutQuote->getShippingAddress()->getShippingAmount();
    }

    private function getInstallmentsOptions($collection, $pagarmeHelper, $installmentConfig)
    {
        $installments = array();
        $checkoutQuote = $this->getCheckoutQuote();

        foreach ($collection as $item) {
            $installmentItem = $item->getInstallment();
            $amount = $item->getInstallmentAmount();
            $installmentAmountInReal = $pagarmeHelper->convertCurrencyFromCentsToReal($amount);
            $formatPrice = $checkoutQuote->getStore()->formatPrice($installmentAmountInReal, false);

            $label = $this->__('%sx - %s', $installmentItem, $formatPrice);

            if ($installmentItem == 1) {
                $installments[$installmentItem] = $this->__('Pay in full - %s', $formatPrice);
                continue;
            }

            $interestLabel = $this->__('interest-free');
            if ($installmentItem > $installmentConfig->getFreeInstallments()) {
                $interestRate = $installmentConfig->getInterestRate();
                $interestLabel = $this->__('monthly interest rate (%s)', $interestRate.'%');
            }

            $installments[$installmentItem] = "{$label} {$interestLabel}";
        }

        return $installments;
    }

    public function getInstallmentsAvailables()
    {
        $pagarmeHelper = Mage::helper('pagarme');
        $creditCardModel = Mage::getModel('pagarme/cc');
        $total = $this->getCheckoutTotalAmount();
        $installmentConfig = $creditCardModel->getPagarMeCcInstallmentConfig();
        $installmentNumber = $creditCardModel->getInstallmentNumber($total, $installmentConfig);
        $installmentConfig->setMaxInstallments($installmentNumber);
        $total = $pagarmeHelper->formatAmount($total);
        $api = $this->getPagarmeAPI();

        $collection = $creditCardModel->getAvailableInstallments($total, $installmentConfig, $api);
        return $this->getInstallmentsOptions($collection, $pagarmeHelper, $installmentConfig);
    }
}
