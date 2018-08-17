<?php

abstract class PagarMe_Core_Model_PostbackHandler_Base
{
    /**
     * @var \Mage_Sales_Model_Order $order
     */
    protected $order;

    /**
     * @var int transaction id on Pagar.me
     */
    protected $transactionId;

    /**
     * Could be any of PagarMe\Sdk\Transaction\AbstractTransaction statuses
     *
     * @var string $oldStatus
     */
    protected $oldStatus;

    /**
     * @param \Mage_Sales_Model_Order $order
     * @param int $transactionId
     * @param string $oldStatus
     */
    public function __construct(
        \Mage_Sales_Model_Order $order,
        $transactionId,
        $oldStatus = null
    ) {
        $this->order = $order;
        $this->transactionId = $transactionId;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Returns the desired state on magento
     *
     * @return string
     */
    abstract protected function getDesiredState();

    final protected function getMessageForHandlerException()
    {
        $message = sprintf(
            'Order [id:%s] [transactionId:%s]',
            $this->order->getId(),
            $this->transactionId
        );

        return $message;
    }

    /**
     * @return \Mage_Sales_Model_Order
     */
    abstract public function process();

    /**
     * Returns true if the order is on desired magento state
     *
     * @return bool
     */
    final protected function isOrderOnDesiredState()
    {
        if ($this->order->getState() === $this->getDesiredState()) {
            return true;
        }

        return false;
    }
}