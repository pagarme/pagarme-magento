<?php

use \PagarMe\Sdk\Transaction\AbstractTransaction;

abstract class PagarMe_Core_Model_OrderStatusHandler_Base
{
    /**
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /**
     * @var AbstractTransaction
     */
    protected $transaction;

    public function __construct(
        Mage_Sales_Model_Order $order,
        AbstractTransaction $transaction
    )
    {
        $this->order = $order;
        $this->transaction = $transaction;
    }

    /**
     * Responsible to handle order status based on transaction status
     */
    abstract public function handleStatus();
}
