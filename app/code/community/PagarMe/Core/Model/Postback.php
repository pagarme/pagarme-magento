<?php

class PagarMe_Core_Model_Postback extends Mage_Core_Model_Abstract
{
    const POSTBACK_STATUS_PAID = 'paid';
    const POSTBACK_STATUS_REFUNDED = 'refunded';

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
     *
     * @return type
     * @throws Exception
     */
    public function processPostback($transactionId, $currentStatus)
    {
        $order = $this->getOrderService()
            ->getOrderByTransactionId($transactionId);

        if (!$this->canProceedWithPostback($order, $currentStatus)) {
            throw new Exception(
                Mage::helper('pagarme_core')->__('Can\'t proccess postback.')
            );
        }

        switch ($currentStatus) {
            case self::POSTBACK_STATUS_PAID:
                $this->setOrderAsPaid($order);
                break;
            case self::POSTBACK_STATUS_REFUNDED:
                $this->setOrderAsRefunded($order);
                break;
        }

        return $order;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    public function setOrderAsPaid($order)
    {
        $invoice = $this->getInvoiceService()
            ->createInvoiceFromOrder($order);

        $invoice->register()
            ->pay();

        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, "pago");

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($order)
            ->addObject($invoice)
            ->save();
    }

    /**
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
}
