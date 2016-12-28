<?php
/**
*  @category   Inovarti
*  @package    Inovarti_Pagarme
*  @copyright   Copyright (C) 2016 Pagar Me (http://www.pagar.me/)
*  @author     Lucas Santos <lucas.santos@pagar.me>
*/
abstract class Inovarti_Pagarme_Model_Abstract
    extends Inovarti_Pagarme_Model_Split
{
    const REQUEST_TYPE_AUTH_CAPTURE = 'AUTH_CAPTURE';
    const REQUEST_TYPE_AUTH_ONLY    = 'AUTH_ONLY';
    const REQUEST_TYPE_CAPTURE_ONLY = 'CAPTURE_ONLY';

    private $pagarmeApi;

    /**
     * Inovarti_Pagarme_Model_Abstract constructor.
     */
    public function __construct()
    {
        $this->pagarmeApi = Mage::getModel('pagarme/api');
    }

    /**
     * @param $payment
     * @param $amount
     * @param $requestType
     * @param bool $checkout
     * @return $this
     */
    protected function _place($payment, $amount, $requestType, $checkout = false)
    {
        if ($requestType === self::REQUEST_TYPE_AUTH_ONLY || $requestType === self::REQUEST_TYPE_AUTH_CAPTURE) {
            $customer = Mage::helper('pagarme')->getCustomerInfoFromOrder($payment->getOrder());
            $requestParams = $this->prepareRequestParams($payment, $amount, $requestType, $customer, $checkout);

			$incrementId = $payment->getOrder()->getQuote()->getIncrementId();
			$requestParams->setMetadata(
				array(
					'order_id' => $incrementId
				)
			);
			$requestParams->setAntifraudMetadata($this->prepareAntifraudMetadata($amount, $requestType, $customer, $checkout));
            $transaction = $this->charge($requestParams);

            $this->prepareTransaction($transaction, $payment, $checkout);
            return $this;
        }
    }

	private function prepareAntifraudMetadata( $amount, $requestType, $customer, $checkout) {

		$customerRegistered = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($customer->getEmail());

		$cart = Mage::getModel('checkout/cart')->getQuote();

		$dateCustomer = new DateTime($customer->getBornAt(), new DateTimeZone(Mage::getStoreConfig('general/locale/timezone')));
		$dateCustomer = $dateCustomer->format('c');

		$shoppingCart = array();
		foreach ($cart->getAllItems() as $item) {
			$itemToAdd = array(
				"id_product" => $item->getProductId(),
				"name" => $item->getProduct()->getName(),
				"quantity" => $item->getQty(),
				"unit_price" => $item->getProduct()->getPrice(),
				"totalAdditions" => 0,
				"totalDiscounts" => ($item->getProduct()->getFinalPrice() < $item->getProduct()->getPrice()) ? ($item->getProduct()->getPrice() - $item->getProduct()->getSpecialPrice())* $item->getQty() : 0
			);
			$shoppingCart[] = $itemToAdd;
		}
		return array(
			"session_id" => Mage::getSingleton("core/session")->getEncryptedSessionId(),
			"ip" => Mage::helper('core/http')->getRemoteAddr(),
			"plataform" => "web",
			"register" => array(
				"id" => (Mage::getSingleton('customer/session')->isLoggedIn() ? Mage::getSingleton('customer/session')->getId() : ""),
				"email" => $customerRegistered->getEmail(),
				"registered_at" => $customerRegistered->getCreatedAt(),
				"login_source" => Mage::getSingleton('checkout/session')->getQuote()->getCheckoutMethod()
			),
			"billing" => array(
				"customer" => array(
					"name" => $customer->getName(),
					"document_number" => $customer->getDocumentNumber(),
					"born_at" => $dateCustomer,
					"gender" => $customer->getGender()
				),
				"address"=> array(
					"country"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('country_id'),
					"state"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('region'),
					"city"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('city'),
					"zipcode" => Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('postcode'),
					"neighborhood"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(4),
					"street"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(1),
					"street_number"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(2),
					"complementary"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(3)
				),
				"phone_numbers"=> array(
					array(
						"ddi" => "55",
						"ddd" => $customer->getPhone('ddd'),
						"number" => $customer->getPhone('number')
					)
				)
			),
			"buyer"=> array(
				"customer"=> array(
					"name" => $customer->getName(),
					"document_number" => $customer->getDocumentNumber(),
					"born_at" => $dateCustomer,
					"gender" => $customer->getGender()
				),
				"address"=> array(
					"country"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('country_id'),
					"state"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('region'),
					"city"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('city'),
					"zipcode" => Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData('postcode'),
					"neighborhood"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(4),
					"street"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(1),
					"street_number"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(2),
					"complementary"=> Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getStreet(3)
				),
				"phone_numbers"=> array(
					array(
						"ddi"=> "55",
						"ddd" => $customer->getPhone('ddd'),
						"number" => $customer->getPhone('number')
					)
				)
			),
			"shipping"=> array(
				"customer"=> array(
					"name" => $customer->getName(),
					"document_number" => $customer->getDocumentNumber(),
					"born_at" => $dateCustomer, 
					"gender" => $customer->getGender()
				),
				"address"=> array(
					"country"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getData('country_id'),
					"state"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getData('region'),
					"city"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getData('city'),
					"zipcode" => Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getData('postcode'),
					"neighborhood"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getStreet(4),
					"street"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getStreet(1),
					"street_number"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getStreet(2),
					"complementary"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getStreet(3)
				),
				"phone_numbers"=> [
					array(
						"ddi"=> "55",
						"ddd" => $customer->getPhone('ddd'),
						"number" => $customer->getPhone('number')
					)
				],
				"shipping_method" => Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getShippingMethod(),
				"fee"=> Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getShippingAmount()
			),
			"shopping_cart" => $shoppingCart
		);

	}

    /**
     * @param Varien_Object $payment
     * @param $amount
     * @return $this
     */
    public function refund(Varien_Object $payment, $amount)
    {
        $transaction = $this->pagarmeApi->refund($payment->getPagarmeTransactionId());
        $this->checkApiErros($transaction);
        $this->prepareTransaction($transaction, $payment);

    	return $this;
    }

    /**
     * @param $requestParams
     * @return mixed
     */
    private function charge($requestParams)
    {
        return $this->pagarmeApi->charge($requestParams);
    }

    /**
     * @param $payment
     * @param $amount
     * @param $requestType
     * @param $customer
     * @param $checkout
     * @return Varien_Object
     */
    private function prepareRequestParams($payment, $amount, $requestType, $customer, $checkout)
    {
        $splitRules = $this->prepareSplit($payment->getOrder()->getQuote());
        $requestParams = new Varien_Object();

        $requestParams->setAmount(Mage::helper('pagarme')->formatAmount($amount))
                ->setCapture($requestType == self::REQUEST_TYPE_AUTH_CAPTURE)
                ->setCustomer($customer);


        if ($splitRules) {
            $requestParams->setSplitRules($splitRules);
        }

        if ($checkout) {
          $requestParams->setPaymentMethod($payment->getPagarmeCheckoutPaymentMethod());
          $requestParams->setCardHash($payment->getPagarmeCheckoutHash());
          $requestParams->setInstallments($payment->getPagarmeCheckoutInstallments());
        } else {
          $requestParams->setPaymentMethod(Inovarti_Pagarme_Model_Api::PAYMENT_METHOD_CREDITCARD);
          $requestParams->setCardHash($payment->getPagarmeCardHash());
          $requestParams->setInstallments($payment->getInstallments());
        }

        if ($this->getConfigData('async')) {
             $requestParams->setAsync(true);
             $requestParams->setPostbackUrl(Mage::getUrl('pagarme/transaction_creditcard/postback'));
		    }

        $incrementId = $payment->getOrder()->getQuote()->getIncrementId();
		    $requestParams->setMetadata(array('order_id' => $incrementId));
        return $requestParams;
    }

    /**
     * @param $transaction
     * @param $payment
     * @param $checkout
     * @return $this
     */
    private function prepareTransaction($transaction,$payment, $checkout)
    {
        $this->checkApiErros($transaction);

        if ($transaction->getStatus() == 'refused') {
            $this->refusedStatus($transaction);
        }

        $payment = $this->preparePaymentMethod($payment,$transaction);

        if ($checkout) {
            $payment->setTransactionAdditionalInfo(
                Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
                  'status' => $transaction->getStatus (),
                  'payment_method' => $transaction->getPaymentMethod (),
                  'boleto_url' => $transaction->getBoletoUrl ()
                  )
            );
        } else {
          $payment->setTransactionAdditionalInfo(
              Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,
              array(
                'status' => $transaction->getStatus()
              )
          );
        }

        if ($this->getConfigData('async')) {
            $payment->setIsTransactionPending(true);
        }

        $payment->setCcOwner($transaction->getCardHolderName())
            ->setCcLast4($transaction->getCardLastDigits())
            ->setCcType(Mage::getSingleton('pagarme/source_cctype')->getTypeByBrand($transaction->getCardBrand()))
            ->setPagarmeTransactionId($transaction->getId())
            ->setPagarmeAntifraudScore($transaction->getAntifraudScore())
            ->setTransactionId($transaction->getId())
            ->setIsTransactionClosed(0)
            ->setInstallments($transaction->getInstallments());

      return $this;
    }

    /**
     * @param $payment
     * @param $transaction
     * @return mixed
     */
    private function preparePaymentMethod($payment,$transaction)
    {
      if ($payment->getPagarmeTransactionId()) {

          $transactionIdSprintf = '%s-%s';
          $transactionId = sprintf(
            $payment->getPagarmeTransactionId(),
            Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE
          );

          $payment->setTransactionId($transactionId)
              ->setParentTransactionId($payment->getParentTransactionId())
              ->setIsTransactionClosed(0);
          return $payment;
      }

      $payment->setCcOwner($transaction->getCardHolderName())
        ->setCcLast4($transaction->getCardLastDigits())
        ->setCcType(Mage::getSingleton('pagarme/source_cctype')->getTypeByBrand($transaction->getCardBrand()))
        ->setPagarmeTransactionId($transaction->getId())
        ->setPagarmeAntifraudScore($transaction->getAntifraudScore())
        ->setTransactionId($transaction->getId())
        ->setIsTransactionClosed(0)
        ->setInstallments($transaction->getInstallments());

      return $payment;
    }

    /**
     * @param $transaction
     */
    private function refusedStatus($transaction)
    {
        $reason = $transaction->getStatusReason();
        Mage::log($this->_wrapGatewayError($reason), null, 'pagarme.log');
        Mage::throwException($this->_wrapGatewayError($reason));
    }

    /**
     * @param $transaction
     * @return $this
     */
    private function checkApiErros($transaction)
    {
        if (!$transaction->getErrors()) {
          return $this;
        }

        $messages = array();
        foreach ($transaction->getErrors() as $error) {

          if ($error->getMessage() == 'card_hash inválido. Para mais informações, consulte nossa documentação em https://pagar.me/docs.') {
            $messages[] = 'Dados do cartão inválidos. Por favor preencha novamente os dados do cartão clicando no botão (Preencher dados do cartão)';
          } else {
            $messages[] = $error->getMessage() . '.';
          }
        }

        Mage::log(implode("\n", $messages), null, 'pagarme.log');
        Mage::throwException(implode("\n", $messages));
    }

    /**
     * @param $code
     * @return string
     */
    protected function _wrapGatewayError($code)
    {
        switch ($code)
        {
        case 'acquirer': { $result = 'Transaction refused by the card company.'; break; }
        case 'antifraud': { $result = 'Transação recusada pelo antifraude.'; break; }
        case 'internal_error': { $result = 'Ocorreu um erro interno ao processar a transação.'; break; }
        case 'no_acquirer': { $result = 'Sem adquirente configurado para realizar essa transação.'; break; }
        case 'acquirer_timeout': { $result = 'Transação não processada pela operadora de cartão.'; break; }
        }

        return Mage::helper('pagarme')->__('Transaction failed, please try again or contact the card issuing bank.') . PHP_EOL
               . Mage::helper('pagarme')->__($result);
    }
}
