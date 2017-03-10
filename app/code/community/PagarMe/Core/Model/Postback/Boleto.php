<?php

class PagarMe_Core_Model_Postback_Boleto extends Mage_Core_Model_Abstract
{
    /**
     * @var PagarMe_Core_Model_Service_Invoice
     */
    protected $invoiceService;

    /**
     * @param Mage_Sales_Model_Order $order
     * @param type $currentStatus
     *
     * @return bool
     */
    private function canProceedWithPostback(Mage_Sales_Model_Order $order, $currentStatus)
    {
        return $order->canInvoice() && $currentStatus == 'paid';
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

        $invoice = $this->getInvoiceService()
            ->createInvoiceFromOrder($order);

        $invoice->register()
            ->pay();

        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, "pago");

        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($order)
            ->addObject($invoice)
            ->save();

        return $order;
    }
}
