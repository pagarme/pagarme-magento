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

class Inovarti_Pagarme_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getOrderIdByTransactionId($tid)
	{
		$resource = Mage::getSingleton('core/resource');
		$conn = $resource->getConnection('core_read');
		$select = $conn->select()
			->from($resource->getTableName('sales/order_payment'))
			->where('pagarme_transaction_id = ?', $tid)
			->reset (Zend_Db_Select::COLUMNS)
			->columns ('parent_id');
		return $conn->fetchOne($select);
	}

	public function formatAmount($amount)
	{
		return number_format($amount, 2, '', '');
	}

	public function formatGender($gender)
	{
		if ($gender == 1) {
			return 'M';
		} elseif ($gender == 2) {
			return 'F';
		}
		return '';
	}

	public function formatDob($date)
	{
		$date = date('m-d-Y', strtotime($date));
		return $date;
	}

	public function splitTelephone($telephone)
	{
		$telephone = Zend_Filter::filterStatic($telephone, 'Digits');
		$ddd = $this->_iSubstr($telephone, 10, 2); //$ddd = substr($telephone, 0, 2);
		$number = $this->_iSubstr($telephone, 8, 8); //$number = substr($telephone, 2);
		$data = array(
			'ddd' => $ddd,
			'number' => $number
		);
		return $data;
	}

	public function getCustomerInfoFromOrder($order)
	{
		$billingAddress = $order->getBillingAddress();

		$address = new Varien_Object();
		$address->setStreet($billingAddress->getStreet(1));
		$address->setStreetNumber($billingAddress->getStreet(2));
		$address->setComplementary($billingAddress->getStreet(3)); // optional
		$address->setNeighborhood($billingAddress->getStreet(4));
		$address->setZipcode(Zend_Filter::filterStatic($billingAddress->getPostcode(), 'Digits'));

		$customer = new Varien_Object();
		$customer->setName($order->getCustomerName($order));
		$customer->setDocumentNumber($this->getCustomerCpf($order));
		$customer->setEmail($order->getCustomerEmail());
		$customer->setPhone($this->splitTelephone($billingAddress->getTelephone()));
		$customer->setSex($this->formatGender($order->getCustomerGender())); // optional
		$customer->setBornAt($this->formatDob($order->getCustomerDob())); // optional
		$customer->setAddress($address);
		Mage::dispatchEvent('pagarme_get_customer_info_from_order_after', array('order' => $order, 'customer_info' => $customer));

		return $customer;
	}

	private function getCustomerCpf($order)
	{
			$customCpfField = Mage::getStoreConfig('payment/pagarme_settings/custom_cpf_field');
			$customCnpjField = Mage::getStoreConfig('payment/pagarme_settings/custom_cnpj_field');

			if (!$customCpfField && !$customCnpjField) {
					return $order->getCustomerTaxvat();
			}

			return $this->getCpfOrCnpj($order->getCustomer(), $customCpfField, $customCnpjField);
	}

	private function getCpfOrCnpj($customer, $customCpfField, $customCnpjField)
	{
  		$cpf = preg_replace( '/[^0-9]/', '', $customer->getData($customCpfField));

			if ($cpf) {
					return $cpf;
			}

			$cnpj = preg_replace( '/[^0-9]/', '', $customer->getData($customCnpjField));
		  return $cnpj;
	}


    public function _validateCustomerAddress($address)
    {
        if (!Zend_Validate::is($address->getStreet(1), 'NotEmpty')) {
            $address->addError(Mage::helper('customer')->__('Please enter the street name.'));
        }

        if (!Zend_Validate::is($address->getStreet(2), 'NotEmpty')) {
            $address->addError(Mage::helper('customer')->__('Please enter the home number.'));
        }

        if (!Zend_Validate::is($address->getStreet(4), 'NotEmpty')) {
            $address->addError(Mage::helper('customer')->__('Please enter the district.'));
        }
    }

	public function getMode()
	{
		$mode = Mage::getStoreConfig('payment/pagarme_settings/mode');
		return $mode;
	}

	public function getApiKey()
	{
		$apiKey = Mage::getStoreConfig('payment/pagarme_settings/apikey_' . $this->getMode());
		return $apiKey;
	}

	public function getEncryptionKey()
	{
		$encryptionKey = Mage::getStoreConfig('payment/pagarme_settings/encryptionkey_' . $this->getMode());
		return $encryptionKey;
	}

    public function _numberOnly ($text)
    {
        return preg_replace ("#[^0-9]#", "", $text);
    }

    // inverse substr with pointer beyond the range
    public function _iSubstr ($text, $start, $length)
    {
        $result = null;
        $position = strlen ($text);

        for ($i = 0; $i < $start; $i ++) $position --;

        for ($j = $position; $j < $position + $length; $j ++) $result .= $text [$j];

        return $result;
    }

    public function getShippingAmount()
    {
        $address = Mage::getModel('checkout/session')->getQuote()->getShippingAddress();
        if (!empty ($address))
        {
            return $address->getShippingAmount();
        }
    }

    public function getBaseSubtotalWithDiscount ()
    {
        return Mage::getModel('checkout/session')->getQuote()->getBaseSubtotalWithDiscount ();
    }
}
