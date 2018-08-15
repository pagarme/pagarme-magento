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
     * @param \Mage_Sales_Model_Order $order
     * @param int $transactionId
     */
    public function __construct(
        \Mage_Sales_Model_Order $order,
        $transactionId
    ) {
        $this->order = $order;
        $this->transactionId = $transactionId;
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