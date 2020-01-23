<?php

use PagarMe_Core_Model_PostbackHandler_Factory as PostbackHandlerFactory;

class PagarMe_Core_Model_Postback extends Mage_Core_Model_Abstract
{
    const POSTBACK_STATUS_PAID = 'paid';
    const POSTBACK_STATUS_REFUNDED = 'refunded';
    const POSTBACK_STATUS_AUTHORIZED = 'authorized';
    const POSTBACK_STATUS_REFUSED = 'refused';
    const POSTBACK_STATUS_ANALYZING = 'analyzing';

    /**
     * @var PagarMe_Core_Model_Service_Order
     */
    protected $orderService;

    /**
     * @var PagarMe_Core_Model_Service_Invoice
     */
    protected $invoiceService;

    /**
     * @param Mage_Sales_Model_Order $order
     * @param string $currentStatus
     *
     * @return bool
     */
    public function canProceedWithPostback(Mage_Sales_Model_Order $order, $currentStatus)
    {
        if ($order->canInvoice() && $currentStatus == self::POSTBACK_STATUS_PAID) {
            return true;
        }

        if ($currentStatus == self::POSTBACK_STATUS_REFUNDED) {
            return true;
        }

        if ($currentStatus == self::POSTBACK_STATUS_AUTHORIZED) {
            return true;
        }

        if ($currentStatus == self::POSTBACK_STATUS_REFUSED) {
            return true;
        }

        if ($currentStatus == self::POSTBACK_STATUS_ANALYZING) {
            return true;
        }

        return false;
    }

    /**
     * @codeCoverageIgnore
     * @return PagarMe_Core_Model_Service_Order
     */
    public function getOrderService()
    {
        if (is_null($this->orderService)) {
            $this->orderService = Mage::getModel('pagarme_core/service_order');
        }

        return $this->orderService;
    }

    /**
     * @codeCoverageIgnore
     * @param PagarMe_Core_Model_Service_Order $orderService
     * @return void
     */
    public function setOrderService(PagarMe_Core_Model_Service_Order $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @codeCoverageIgnore
     * @return PagarMe_Core_Model_Service_Invoice
     */
    public function getInvoiceService()
    {
        if (is_null($this->invoiceService)) {
            $this->invoiceService = Mage::getModel('pagarme_core/service_invoice');
        }

        return $this->invoiceService;
    }

    /**
     * @codeCoverageIgnore
     * @param PagarMe_Core_Model_Service_Invoice $invoiceService
     * @return void
     */
    public function setInvoiceService(PagarMe_Core_Model_Service_Invoice $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * @param int $transactionId
     * @param string $currentStatus
     * @param string $oldStatus
     *
     * @return Mage_Sales_Model_Order
     * @throws Exception|PagarMe_Core_Model_PostbackHandler_Exception
     */
    public function processPostback($transactionId, $currentStatus, $oldStatus)
    {
        $order = $this->getOrderService()
            ->getOrderByTransactionId($transactionId);

        $postbackHandler = PostbackHandlerFactory::createFromDesiredStatus(
            $currentStatus,
            $oldStatus,
            $order,
            $transactionId
        );

        return $postbackHandler->process();
    }

    /**
     * @deprecated
     * @see PagarMe_Core_Model_PostbackHandler_Paid::process()
     *
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    public function setOrderAsPaid($order)
    {
        $invoice = $this->getInvoiceService()->createInvoiceFromOrder($order);
        $invoice->register()->pay();

        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'pago', true);
        $invoice->sendEmail();

        Mage::getModel('core/resource_transaction')
            ->addObject($order)
            ->addObject($invoice)
            ->save();
    }

    /**
     * @deprecated
     * @see PagarMe_Core_Model_PostbackHandler_Authorized::process()
     *
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    public function setOrderAsAuthorized($order)
    {
        $order->setState(
            Mage_Sales_Model_Order::STATE_PROCESSING,
            true,
            Mage::helper('sales')->__(
                'Authorized amount of %s.',
                substr('R$'.$order->getGrandTotal(), 0, -2)
            )
        );

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($order)
            ->save();
    }

    /**
     * @deprecated
     * @see \PagarMe_Core_Model_PostbackHandler_Refunded::process()
     *
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    public function setOrderAsRefunded($order)
    {
        $orderService = Mage::getModel('sales/service_order', $order);

        $invoices = [];

        foreach ($order->getInvoiceCollection() as $invoice) {
            if ($invoice->canRefund()) {
                $invoices[] = $invoice;
            }
        }

        $transaction = Mage::getModel('core/resource_transaction');

        foreach ($invoices as $invoice) {
            $creditmemo = $orderService->prepareInvoiceCreditmemo($invoice);
            $creditmemo->setRefundRequested(true);
            $creditmemo->setOfflineRequested(true);
            $creditmemo->setPaymentRefundDisallowed(true)->register();
            $transaction->addObject($creditmemo);
        }
        $transaction->addObject($order)->save();
        return $order;
    }

    /**
     * @deprecated
     * @see \PagarMe_Core_Model_PostbackHandler_Refused::process()
     *
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    public function setOrderAsRefused($order)
    {
        $transaction = Mage::getModel('core/resource_transaction');

        $order->setState(
            Mage_Sales_Model_Order::STATE_CANCELED,
            true,
            Mage::helper('pagarme_core')->
                __('Refused by gateway.')
        );

        $transaction->addObject($order)->save();
        
        return $order;
    }
}
