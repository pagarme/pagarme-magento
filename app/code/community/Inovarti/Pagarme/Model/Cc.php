<?php
/**
*  @category   Inovarti
*  @package    Inovarti_Pagarme
*  @copyright  Copyright (C) 2016 Pagar Me (http://www.pagar.me/)
*  @author     Lucas Santos <lucas.santos@pagar.me>
*/

class Inovarti_Pagarme_Model_Cc extends Inovarti_Pagarme_Model_Abstract
{
    protected $_code                        = 'pagarme_cc';
    protected $_formBlockType               = 'pagarme/form_cc';
    protected $_infoBlockType               = 'pagarme/info_cc';
    protected $_isGateway                   = true;
    protected $_canAuthorize                = true;
    protected $_canCapture                  = true;
    protected $_canRefund                   = true;
    protected $_canUseForMultishipping      = true;
    protected $_canManageRecurringProfiles  = false;

    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }

        $info = $this->getInfoInstance();

        $info->setInstallments($data->getInstallments())
            ->setInstallmentDescription($data->getInstallmentDescription())
            ->setPagarmeCardHash($data->getPagarmeCardHash());

        return $this;
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        $this->_place($payment, $this->getGrandTotalFromPayment($payment), self::REQUEST_TYPE_AUTH_ONLY);
        return $this;
    }

    public function capture(Varien_Object $payment, $amount)
    {
        $amount = $this->getGrandTotalFromPayment($payment);

        if ($payment->getPagarmeTransactionId()) {
            $this->_place($payment, $amount, self::REQUEST_TYPE_CAPTURE_ONLY);
            return $this;
        }

        $this->_place($payment, $amount, self::REQUEST_TYPE_AUTH_CAPTURE);
        return $this;
    }

    public function calculateInterestFeeAmount($amount, $numberOfInstallments, $installmentConfig)
    {
        $availableInstallments = $this->getAvailableInstallments($amount, $installmentConfig);

        if(!$availableInstallments)
            return null;

        $installment = array_shift(array_filter($availableInstallments,
            function ($availableInstallment) use ($numberOfInstallments) {
                return $availableInstallment->getInstallment() == $numberOfInstallments;
            }
        ));

        if($installment != null)
            return Mage::helper('pagarme')->convertCurrencyFromCentsToReal(($installment->getAmount() - $amount));
        return 0;
    }

    private function getAvailableInstallments($amount, $installmentConfig)
    {
        $data = new Varien_Object();
        $data->setMaxInstallments($installmentConfig->getMaxInstallments());
        $data->setFreeInstallments($installmentConfig->getFreeInstallments());
        $data->setInterestRate($installmentConfig->getInterestRate());
        $data->setAmount($amount);

        $api = Mage::getModel('pagarme/api');
        return $api->calculateInstallmentsAmount($data)
            ->getInstallments();
    }
}
