<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Pagarme_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getOrderIdByTransactionId($tid)
	{
		$resource = Mage::getSingleton('core/resource');
		$conn = $resource->getConnection('core_read');
		$select = $conn->select()
			->from($resource->getTableName('sales/order_payment'))
			->where('pagarme_transaction_id = ?', $tid);
		$result = $conn->fetchAll($select);
		return $result[0]['parent_id'];
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
		$ddd = substr($telephone, 0, 2);
		$number = substr($telephone, 2);
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
		$customer->setName($order->getCustomerName());
		$customer->setDocumentNumber($order->getCustomerTaxvat());
		$customer->setEmail($order->getCustomerEmail());
		$customer->setPhone($this->splitTelephone($billingAddress->getTelephone()));
		$customer->setSex($this->formatGender($order->getCustomerGender())); // optional
		$customer->setBornAt($this->formatDob($order->getCustomerDob())); // optional
		$customer->setAddress($address);
		Mage::dispatchEvent('pagarme_get_customer_info_from_order_after', array('order' => $order, 'customer_info' => $customer));

		return $customer;
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
}
