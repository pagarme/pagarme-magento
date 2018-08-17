<?php

use \PagarMe\Sdk\Transaction\AbstractTransaction;
use PagarMe_Core_Model_PostbackHandler_Authorized as AuthorizeHandler;

class PagarMe_Core_Model_PostbackHandler_Paid extends PagarMe_Core_Model_PostbackHandler_Base
{
    /**
     * Given a paid postback the desired status on magento is processing
     */
    const MAGENTO_DESIRED_STATUS = Mage_Sales_Model_Order::STATE_PROCESSING;

    /**
     * @codeCoverageIgnore
     * @return PagarMe_Core_Model_Service_Invoice
     */
    private function getInvoiceService()
    {
        return Mage::getModel(
            'pagarme_core/service_invoice'
        );
    }

    public function getDesiredState()
    {
        return self::MAGENTO_DESIRED_STATUS;
    }

    /**
     * @return bool
     */
    private function isOrderInPaymentReview()
    {
        return
            $this->order->getState() ===
            Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
    }

    /**
     * Runs only if the only if the order's old status is Pagarme::processing
     * It is necessary to create a new invoice
     *
     * @return void
     */
    private function setOrderAsProcessing()
    {
        Mage::log($this->oldStatus);
        if (
            $this->isOrderInPaymentReview() &&
            $this->oldStatus === AbstractTransaction::PROCESSING
        ) {
            $this->order->setState(
                self::MAGENTO_DESIRED_STATUS
            );
        }
    }

    /**
     * Validate that the given order can be updated
     *
     * @throws PagarMe_Core_Model_PostbackHandler_Exception
     * @return void
     */
    protected function canProcess()
    {
        $message = $this->getMessageForHandlerException();

        if (!$this->order->canInvoice()) {
            $message .= ' can\'t be invoiced';
            throw new PagarMe_Core_Model_PostbackHandler_Exception($message);
        }
    }

    /**
     * @return \Mage_Sales_Model_Order
     * @throws PagarMe_Core_Model_PostbackHandler_Exception
     */
    public function process()
    {
        $this->setOrderAsProcessing();
        $this->canProcess();
        $invoice = $this
            ->getInvoiceService()
            ->createInvoiceFromOrder($this->order);

        $invoice->register()->pay();

        $this->order->setState(
            self::MAGENTO_DESIRED_STATUS,
            true,
            "pago"
        );

        Mage::getModel('core/resource_transaction')
            ->addObject($this->order)
            ->addObject($invoice)
            ->save();

        return $this->order;
    }
}