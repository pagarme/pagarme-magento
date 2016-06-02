<?php
/**
*  @category   Inovarti
*  @package    Inovarti_Pagarme
*  @copyright   Copyright (C) 2016 Pagar Me (http://www.pagar.me/)
*  @author     Lucas Santos <lucas.santos@pagar.me>
*/

class Inovarti_Pagarme_Model_Checkout extends Inovarti_Pagarme_Model_Abstract
{
    const REQUEST_TYPE_AUTH_CAPTURE = 'AUTH_CAPTURE';
    const REQUEST_TYPE_AUTH_ONLY    = 'AUTH_ONLY';
    const REQUEST_TYPE_CAPTURE_ONLY = 'CAPTURE_ONLY';

    protected $_code = 'pagarme_checkout';
    protected $_formBlockType = 'pagarme/form_checkout';
    protected $_infoBlockType = 'pagarme/info_checkout';

    protected $_isGateway                   = true;
    protected $_canAuthorize                = true;
    protected $_canCapture                  = true;
    protected $_canRefund                   = true;
    protected $_canUseForMultishipping 		= false;
    protected $_canManageRecurringProfiles  = false;

    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object ($data);
        }

        $info = $this->getInfoInstance ();
        $info->setPagarmeCheckoutInstallments ($data->getPagarmeCheckoutInstallments ())
            ->setPagarmeCheckoutPaymentMethod ($data->getPagarmeCheckoutPaymentMethod ())
            ->setPagarmeCheckoutHash ($data->getPagarmeCheckoutHash ());

        return $this;
    }

    public function authorize(Varien_Object $payment, $amount)
    {
      	$this->_place($payment, $amount, self::REQUEST_TYPE_AUTH_ONLY, true);
        return $this;
    }

    public function capture(Varien_Object $payment, $amount)
    {
      	if ($payment->getPagarmeTransactionId()) {
        		$this->_place($payment, $amount, self::REQUEST_TYPE_CAPTURE_ONLY, true);
            return $this;
      	}

    		$this->_place($payment, $amount, self::REQUEST_TYPE_AUTH_CAPTURE, true);
        return $this;
    }
}
