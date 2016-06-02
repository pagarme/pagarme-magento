<?php
/**
*  @category   Inovarti
*  @package    Inovarti_Pagarme
*  @copyright   Copyright (C) 2016 Pagar Me (http://www.pagar.me/)
*  @author     Lucas Santos <lucas.santos@pagar.me>
*/

class Inovarti_Pagarme_Model_Cc extends Inovarti_Pagarme_Model_Abstract
{
    protected $_code = 'pagarme_cc';
    protected $_formBlockType = 'pagarme/form_cc';
    protected $_infoBlockType = 'pagarme/info_cc';
		protected $_isGateway                   = true;
		protected $_canAuthorize                = true;
		protected $_canCapture                  = true;
		protected $_canRefund                   = true;
		protected $_canUseForMultishipping 			= false;
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
  	  	$this->_place($payment, $payment->getBaseAmountOrdered (), self::REQUEST_TYPE_AUTH_ONLY);
  	      return $this;
    }

  	public function capture(Varien_Object $payment, $amount)
  	{
    		if ($payment->getPagarmeTransactionId()) {
    			$this->_place($payment, $payment->getBaseAmountAuthorized (), self::REQUEST_TYPE_CAPTURE_ONLY);
          return $this;
    		}

  			$this->_place($payment, $payment->getBaseAmountAuthorized (), self::REQUEST_TYPE_AUTH_CAPTURE);
        return $this;
  	}

}
