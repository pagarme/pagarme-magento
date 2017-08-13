<?php
/*
 * @package     Inovarti_Pagarme
 * @copyright   Copyright (C) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Inovarti_Pagarme_Transaction_CreditcardController
    extends Mage_Core_Controller_Front_Action
{

    public function postbackAction()
    {
        $pagarme = Mage::getModel('pagarme/api');
        $request = $this->getRequest();

        if ($request->isPost() && $pagarme->validateFingerprint($request->getPost('id'), $request->getPost('fingerprint'))) {

            $orderId = Mage::helper('pagarme')->getOrderIdByTransactionId($request->getPost('id'));
            $order = Mage::getModel('sales/order')->load($orderId);

            $currentStatus = $request->getPost('current_status');

            if ($currentStatus === Inovarti_Pagarme_Model_Api::TRANSACTION_STATUS_PAID) {

                if (!$order->getId()) {
                    throw new Exception('Invalid order number.');
                }

                if (!$order->hasInvoices()) {
                    throw new Exception('Order does not have any invoices.');
                }

                foreach ($order->getInvoiceCollection() as $invoice) {
                    $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                    $invoice->pay();
                    $invoice->getOrder()->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Invoice defined as paid via Creditcard postback.');

                    Mage::getModel('core/resource_transaction')
                        ->addObject($invoice)
                        ->addObject($invoice->getOrder())
                        ->save();
                }

                $invoice->sendEmail();
                $order->addStatusHistoryComment($this->__('Approved by Pagarme via Creditcard postback.'))->save();

                return $this->getResponse()->setBody('ok');
            }

            if ($this->isCancel($request)) {
                foreach ($order->getInvoiceCollection() as $invoice) {
                    if (!$invoice->canCancel()) {
                        Mage::throwException($this->__('Invoice does not allow to be canceled.'));
                    }
                    $invoice->cancel();
                }
            }

            if (!$order->canCancel()) {
                Mage::throwException($this->__('Order does not allow to be canceled.'));
            }

            $order->cancel()->save();
            $order->addStatusHistoryComment($this->__('Canceled by Pagarme via Creditcard postback.'))->save();

            return $this->getResponse()->setBody('ok');
        }

        $this->_forward('400');
    }

    protected function isCancel($request)
    {
        return in_array(
            $request->getPost('current_status'),
            array(
                Inovarti_Pagarme_Model_Api::TRANSACTION_STATUS_REFUSED,
                Inovarti_Pagarme_Model_Api::TRANSACTION_STATUS_REFUNDED
            )
        );
    }
}
