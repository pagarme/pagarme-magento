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

class Inovarti_Pagarme_Model_Api
{
	const VERSION 	= '1';
	const ENDPOINT 	= 'https://api.pagar.me';

	const PAYMENT_METHOD_BOLETO = 'boleto';
	const PAYMENT_METHOD_CREDITCARD = 'credit_card';
    const PAYMENT_METHOD_TRANSACTIONS = 'transactions';

	const TRANSACTION_STATUS_PROCESSING = 'processing';
	const TRANSACTION_STATUS_AUTHORIZED = 'authorized';
	const TRANSACTION_STATUS_PAID = 'paid';
	const TRANSACTION_STATUS_WAITING_PAYMENT = 'waiting_payment';
	const TRANSACTION_STATUS_REFUSED = 'refused';
	const TRANSACTION_STATUS_REFUNDED = 'refunded';

	protected $_apiKey;
	protected $_encryptionKey;

	public function __construct()
    {
    	$this->_apiKey = Mage::helper('pagarme')->getApiKey();
    	$this->_encryptionKey = Mage::helper('pagarme')->getEncryptionKey();
    }

    /**
	 * Set API Key
	 *
	 * @param string $key
	 * @return Inovarti_Pagarme_Model_Api
	 */
	public function setApiKey($key)
	{
		$this->_apiKey = $key;
		return $this;
	}

	/**
	 * Get API Key
	 *
	 * @return string
	 */
	public function getApiKey()
	{
		if (!$this->_apiKey) {
			Mage::log(Mage::helper('pagarme')->__('You need to configure API key before performing requests.'), null, 'pagarme.log');
			Mage::throwException(Mage::helper('pagarme')->__('You need to configure API key before performing requests.'));
		}
		return $this->_apiKey;
	}

	/**
	 * Set Encryption Key
	 *
	 * @param string $key
	 * @return Inovarti_Pagarme_Model_Api
	 */
	public function setEncryptionKey($key)
	{
		$this->_encryptionKey = $key;
		return $this;
	}

	/**
	 * Get Encryption Key
	 *
	 * @return string
	 */
	public function getEncryptionKey()
	{
		if (!$this->_encryptionKey) {
			Mage::throwException(Mage::helper('pagarme')->__('You need to configure Encryption key before performing requests.'));
		}
		return $this->_encryptionKey;
	}

	/**
	 * Authorize or Authorize and Capture a transaction
	 *
	 * @param Varien_Object $data
	 * @return Varien_Object
	 */
	public function charge(Varien_Object $data)
	{
		$data->setApiKey($this->getApiKey());
		$response = $this->request($this->getTransactionUrl(), $data, Zend_Http_Client::POST);

        $result = $response->getData ();
        if (empty ($result))
        {
			Mage::log($this->__('The order does not allow creating an invoice.'), null, 'pagarme.log');
            Mage::throwException($this->_wrapGatewayError());
        }

		return $response;
	}

	/**
	 * Capture a previously authorized transaction
	 *
	 * @param int $id
	 * @return Varien_Object
	 */
	public function capture($id)
	{
		$data = new Varien_Object();
		$data->setApiKey($this->getApiKey());
		$response = $this->request($this->getTransactionCaptureUrl($id), $data, Zend_Http_Client::POST);

        $result = $response->getData ();
        if (empty ($result))
        {
            Mage::throwException($this->_wrapGatewayError());
        }

		return $response;
	}

	/**
	 * Refund a previously captured transaction
	 *
	 * @param int $id
	 * @return Varien_Object
	 */
	public function refund($id)
	{
		$data = new Varien_Object();
		$data->setApiKey($this->getApiKey());
		$response = $this->request($this->getTransactionRefundUrl($id), $data, Zend_Http_Client::POST);
		return $response;
	}

	/**
	 * Retrieve transaction info
	 *
	 * @param int @id
	 * @return Varien_Object
	 */
	public function find($id)
	{
		$data = new Varien_Object();
		$data->setApiKey($this->getApiKey());
		$response = $this->request($this->getTransactionUrl($id), $data);
		return $response;
	}

	/**
	 * Calculate installments amount
	 *
	 * @param Varien_Object $data
	 * @return Varien_Object
	 */
	public function calculateInstallmentsAmount(Varien_Object $data)
	{
		$data->setEncryptionKey($this->getEncryptionKey());
		$response = $this->request($this->getTransactionCalculateInstallmentsAmountUrl(), $data);
		return $response;
	}

	/**
	 * Send the HTTP request and return an HTTP response object
	 *
	 * @param string $url
	 * @param Varien_Object $data
	 * @param string $method
	 * @return Varien_Object
	 */
	public function request($url, Varien_Object $data, $method='GET')
	{
		$client = new Varien_Http_Client($url, array('timeout'	=> 30));
		$client->setMethod($method);
		$client->setHeaders('Accept-Encoding: identity');
		if ($method == Zend_Http_Client::POST) {
			$client->setParameterPost($this->_parseArray($data));
		} else {
			$client->setParameterGet($this->_parseArray($data));
		}

		$response = $client->request();
		$body = json_decode($response->getBody(), true);
		$result = $this->_parseObject($body);
		return $result;
	}

	/**
	 * Validate Fingerprint
	 *
	 * @param int $id
	 * @param string $fingerprint
	 * @return bool
	 */
	public function validateFingerprint($id, $fingerprint)
	{
		$isValid = sha1($id . '#' . $this->getApiKey()) == $fingerprint;
		return $isValid;
	}

	/**
	 * Retrieve base URL
	 *
	 * @return string
	 */
	public function getBaseUrl()
	{
		$url = self::ENDPOINT . '/' . self::VERSION;
		return $url;
	}

	/**
	 * Retrieve transaction URL
	 *
	 * @param int $id
	 * @return string
	 */
	public function getTransactionUrl($id=null)
	{
		$url = $this->getBaseUrl() . '/transactions';
		if ($id) {
			$url .= '/' . $id;
		}
		return $url;
	}

	/**
	 * Retrieve transaction capture URL
	 *
	 * @param int $id
	 * @return string
	 */
	public function getTransactionCaptureUrl($id)
	{
		$url = $this->getBaseUrl() . '/transactions/' . $id . '/capture';
		return $url;
	}

	/**
	 * Retrieve transaction refund URL
	 *
	 * @param int $id
	 * @return string
	 */
	public function getTransactionRefundUrl($id)
	{
		$url = $this->getBaseUrl() . '/transactions/' . $id . '/refund';
		return $url;
	}

	/**
	 * Retrieve transaction card hash URL
	 *
	 * @return string
	 */
	public function getTransactionCardhashUrl()
	{
		$url = $this->getBaseUrl() . '/transactions/card_hash_key';
		return $url;
	}

	/**
	 * Retrieve transaction calculate installments amount URL
	 *
	 * @return string
	 */
	public function getTransactionCalculateInstallmentsAmountUrl()
	{
		$url = $this->getBaseUrl() . '/transactions/calculate_installments_amount';
		return $url;
	}

	/**
	 * Retrieve customer URL
	 *
	 * @param int $id
	 * @return string
	 */
	public function getCustomerUrl($id=null)
	{
		$url = $this->getBaseUrl() . '/customers';
		if ($id) {
			$url .= '/' . $id;
		}
		return $url;
	}

	/**
	 * Convert an Array to Varien_Object
	 *
	 * @param array
	 * @return Varien_Object
	 */
	protected function _parseObject(array $data)
	{
		$object = new Varien_Object();
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				if ($this->_isAssoc($value)) {
					$object->setData($key, $this->_parseObject($value));
				} else {
					$items = array();
					foreach ($value as $itemKey => $itemValue) {
						$items[$itemKey] = $this->_parseObject($itemValue);
					}
					$object->setData($key, $items);
				}
			} else {
				$object->setData($key, $value);
			}
		}
		return $object;
	}

	/**
	 * Convert a Varien_Object to Array
	 *
	 * @param Varien_Object
	 * @return array
	 */
	protected function _parseArray(Varien_Object $object)
	{
		$array = array();
		foreach ($object->getData() as $key => $value) {
			if ($value instanceof Varien_Object) {
				$array[$key] = $this->_parseArray($value);
			} elseif (is_array($value)) {
				$items = array();
				foreach ($value as $itemKey => $itemValue) {
					if ($itemValue instanceof Varien_Object) {
						$items[$itemKey] = $this->_parseArray($itemValue);
					} else {
						$items[$itemKey] = $itemValue;
					}
				}
				$array[$key] = $items;
			} else {
				$array[$key] = $value;
			}
		}
		return $array;
	}

	/**
	 * Check if array is associative or sequential
	 *
	 * @param array $array
	 * @return bool
	 */
	protected function _isAssoc($array) {
	  return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

    protected function _wrapGatewayError()
    {
        return Mage::helper('pagarme')->__('Transaction failed, please try again or contact the card issuing bank.');
    }
}
