<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Pagarme_Transaction_BoletoController extends Mage_Core_Controller_Front_Action
{
	public function postbackAction()
	{
		$pagarme = Mage::getModel('pagarme/api');
		$request = $this->getRequest();

		if ($request->isPost()
			&& $pagarme->validateFingerprint($request->getPost('id'), $request->getPost('fingerprint'))
			&& $request->getPost('current_status') == Inovarti_Pagarme_Model_Api::TRANSACTION_STATUS_PAID
		) {
			$orderId = Mage::helper('pagarme')->getOrderIdByTransactionId($request->getPost('id'));
			$order = Mage::getModel('sales/order')->load($orderId);
			if (!$order->canInvoice()) {
				Mage::log($this->__('The order does not allow creating an invoice.'), null, 'pagarme.log');
				Mage::throwException($this->__('The order does not allow creating an invoice.'));
			}

			$invoice = Mage::getModel('sales/service_order', $order)
				->prepareInvoice()
				->register()
				->pay();

			$invoice->setEmailSent(true);
			$invoice->getOrder()->setIsInProcess(true);

			$transactionSave = Mage::getModel('core/resource_transaction')
				->addObject($invoice)
				->addObject($invoice->getOrder())
				->save();

			$invoice->sendEmail();

			$this->getResponse()->setBody('ok');
			return;
		}

		$this->_forward('404');
	}
}
