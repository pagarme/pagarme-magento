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
     * Runs only if the order state is payment review.
     * Move order state to Pending Payment, then create the invoice.
     *
     * @return void
     */
    private function setOrderAsProcessing()
    {
        if (
            $this->isOrderInPaymentReview()
        ) {
            $this->order->setState(
                self::MAGENTO_DESIRED_STATUS,
                true,
                Mage::helper('pagarme_core')
                    ->__('Paid'),
                true
            );
        }
    }

    /**
     * @return \Mage_Sales_Model_Order
     * @throws PagarMe_Core_Model_PostbackHandler_Exception
     */
    public function process()
    {
        // Cannot create invoice for PaymentReview Orders
        $this->setOrderAsProcessing();

        if (!$this->order->canInvoice()) {
            $message = $this->buildMessageForHandlerException();
            $message .= ' can\'t be invoiced';
            throw new PagarMe_Core_Model_PostbackHandler_Exception($message);
        }

        $invoice = $this
            ->getInvoiceService()
            ->createInvoiceFromOrder($this->order);

        $invoice->setBaseGrandTotal($this->order->getGrandTotal());
        $invoice->setGrandTotal($this->order->getGrandTotal());
        $invoice->setInterestAmount($this->order->getInterestAmount());

        $invoice->register()->pay();

        if ($this->order->getState() !== $this->getDesiredState()) {
            $this->order->setState(
                self::MAGENTO_DESIRED_STATUS,
                true,
                Mage::helper('pagarme_core')
                    ->__('Paid'),
                true
            );
        }

        Mage::getModel('core/resource_transaction')
            ->addObject($this->order)
            ->addObject($invoice)
            ->save();

        return $this->order;
    }
}
