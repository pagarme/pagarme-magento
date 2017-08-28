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

        if ($pagarme->validateFingerprint($request->getPost('id'), $request->getPost('fingerprint')) !== true
            || $request->isPost() !== true) {
            $this->_forward('400');
        }

        if ($request->getPost('current_status') == Inovarti_Pagarme_Model_Api::TRANSACTION_STATUS_WAITING_PAYMENT) {
            $orderId = Mage::helper('pagarme')->getOrderIdByTransactionId($request->getPost('id'));
            $order = Mage::getModel('sales/order')->load($orderId);
            $postbackTransaction = $request->getPost('transaction');
            $payment = $order->getPayment();

            $payment->setPagarmeBoletoUrl($postbackTransaction['boleto_url'])
                ->setPagarmeBoletoBarcode($postbackTransaction['boleto_barcode'])
                ->setPagarmeBoletoExpirationDate($postbackTransaction['boleto_expiration_date'])
                ->save();

            $order->addStatusHistoryComment($this->__('Update by Pagar.me postback: boleto generated'))
                ->save();

            $this->getResponse()->setBody('Ok - Boleto processed');
            return;
        } else if ($request->getPost('current_status') == Inovarti_Pagarme_Model_Api::TRANSACTION_STATUS_PAID) {
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

            $sendEmail = Mage::getStoreConfig('payment/pagarme_boleto/email_status_change');

            $invoice->setEmailSent(true);
            $invoice->getOrder()->setIsInProcess(true);

            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder())
                ->save();

            $order->addStatusHistoryComment($this->__('Update by Pagar.me postback: boleto is paid'))
                ->save();

            $invoice->sendEmail($sendEmail);
            $this->getResponse()->setBody('Ok - Boleto paid');
            return;
        }

        $this->_forward('400');
    }
}
