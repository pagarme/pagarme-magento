<?php

abstract class PagarMe_Core_Model_PostbackHandler_Base
{
    /**
     * @var \Mage_Sales_Model_Order $order
     */
    protected $order;

    /**
     * @var int $transactionId Pagar.me Transaction Id
     */
    protected $transactionId;

    /**
     * @var string $oldStatus Any of PagarMe\Sdk\Transaction\AbstractTransaction
     * statuses
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

    final protected function buildMessageForHandlerException()
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
        return ($this->order->getState() === $this->getDesiredState());
    }
}