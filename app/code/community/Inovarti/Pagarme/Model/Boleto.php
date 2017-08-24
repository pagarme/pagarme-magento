<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Pagarme_Model_Boleto extends Inovarti_Pagarme_Model_Split
{
    protected $_code = 'pagarme_boleto';
    protected $_formBlockType = 'pagarme/form_boleto';
    protected $_infoBlockType = 'pagarme/info_boleto';

  	protected $_isGateway                   = true;
  	protected $_canUseForMultishipping 		= true;
  	protected $_isInitializeNeeded      	= true;
  	protected $_canManageRecurringProfiles  = false;

    /**
     * @param string $paymentAction
     * @param object $stateObject
     * @return $this
     */
  	public function initialize($paymentAction, $stateObject)
    {
      	$payment = $this->getInfoInstance();
        $order = $payment->getOrder();
        $this->_place($payment, $order->getBaseTotalDue());
        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param $amount
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function _place(Mage_Sales_Model_Order_Payment $payment, $amount)
    {
        $order = $payment->getOrder();
        $customer = Mage::helper('pagarme')->getCustomerInfoFromOrder($payment->getOrder());

        $splitRules = $this->prepareSplit($order->getQuote());
        $requestParams = new Varien_Object();

        $requestParams->setPaymentMethod(Inovarti_Pagarme_Model_Api::PAYMENT_METHOD_BOLETO)
            ->setAmount(Mage::helper('pagarme')->formatAmount($amount))
            ->setBoletoExpirationDate($this->_generateExpirationDate())
            ->setCustomer($customer)
            ->setPostbackUrl(Mage::getUrl('pagarme/transaction_boleto/postback'));
        
        $incrementId = $order->getIncrementId();
        
        $requestParams->setMetadata(array('order_id' => $incrementId));

        if ($splitRules) {
            $requestParams->setSplitRules($splitRules);
        }

        if (!$this->getConfigData('async')) {
            $payment->setIsTransactionPending(false);
            $requestParams->setAsync(false);
        }

        $pagarme = Mage::getModel('pagarme/api');

    		$transaction = $pagarme->charge($requestParams);
    		if ($transaction->getErrors()) {

    			$messages = array();
    			foreach ($transaction->getErrors() as $error) {
    				$messages[] = $error->getMessage() . '.';
    			}

    			Mage::log(implode("\n", $messages), null, 'pagarme.log');
    			Mage::throwException(implode("\n", $messages));
    		}

	      $payment->setPagarmeTransactionId($transaction->getId())
      			->setPagarmeBoletoUrl($transaction->getBoletoUrl()) // PS: Pagar.me in test mode always returns NULL
      			->setPagarmeBoletoBarcode($transaction->getBoletoBarcode())
      			->setPagarmeBoletoExpirationDate($transaction->getBoletoExpirationDate());

        return $this;
    }

    protected function _generateExpirationDate()
    {
        $days = $this->getConfigData('days_to_expire');
        $result = Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime("+ $days days"));
        return $result;
    }
}
