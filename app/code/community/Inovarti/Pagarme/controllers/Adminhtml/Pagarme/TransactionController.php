<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 */

class Inovarti_Pagarme_Adminhtml_Pagarme_TransactionController extends Mage_Adminhtml_Controller_Action
{
	public function viewAction()
	{
		$id = $this->getRequest()->getParam('id');
		$pagarme = Mage::getModel('pagarme/api');

		$result = array();
		$result['success'] = false;
		try {
			$transaction = $pagarme->find($id);
			if ($transaction->getId()) {
				$result['content_html'] = $this->_getTransactionHtml($transaction);
				$result['success'] = true;
			} else {
				$messages = array();
				foreach ($transaction->getErrors() as $error) {
					$messages[] = $error->getMessage() . '.';
				}
				Mage::log(implode("\n", $messages), null, 'pagarme.log');
				Mage::throwException(implode("\n", $messages));
			}
		} catch (Exception $e) {
			Mage::log($e, null, 'pagarme.log');
            $result['error_message'] = $e->getMessage();
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}

	protected function _getTransactionHtml($transaction)
    {
    	$this->loadLayout();
    	$blockType = $transaction->getPaymentMethod() == Inovarti_Pagarme_Model_Api::PAYMENT_METHOD_BOLETO ? 'boleto' : 'cc';
    	$block = $this->getLayout()->createBlock('pagarme/adminhtml_transaction_view_'. $blockType);
    	$block->setTransaction($transaction);
    	return $block->toHtml();
    }
}
