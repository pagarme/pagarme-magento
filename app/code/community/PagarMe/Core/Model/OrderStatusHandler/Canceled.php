<?php

use PagarMe_Core_Model_OrderStatusHandler_Base as BaseHandler;

class PagarMe_Core_Model_OrderStatusHandler_Canceled extends BaseHandler
{
    /**
     * @var string cancel message to be displayed on order's history comments
     */
    private $cancelMessage;


    public function __construct(
        Mage_Sales_Model_Order $order,
        \PagarMe\Sdk\Transaction\AbstractTransaction $transaction,
        $cancelMessage
    ) {
        $this->cancelMessage = $cancelMessage;
        parent::__construct($order, $transaction);
    }

    /**
     * Cancel an order with custom message
     *
     * @throws \Mage_Core_Exception
     */
    private function cancel()
    {
        if ($this->order->canCancel()) {
            $cancelMessage = Mage::helper('pagarme_core')
                ->__($this->cancelMessage);

            $this->order->getPayment()->cancel();
            $this->order->registerCancellation($cancelMessage);

            Mage::dispatchEvent(
                'order_cancel_after',
                ['order' => $this->order]
            );
        }
    }

    /**
     * Responsible to handle order status based on transaction status
     */
    public function handleStatus()
    {
        $magentoTransaction = Mage::getModel(
            'core/resource_transaction'
        );

        $this->cancel();

        $magentoTransaction->addObject($this->order)->save();

        $logMessage = sprintf(
            'order %s, transaction %s updated to %s',
            $this->order->getId(),
            $this->transaction->getId(),
            Mage_Sales_Model_Order::STATE_CANCELED
        );

        Mage::log($logMessage);

        return $this->order;
    }
}
