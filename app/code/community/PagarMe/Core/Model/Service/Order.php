<?php

class PagarMe_Core_Model_Service_Order
{
    /**
     * @codeCoverageIgnore
     *
     * @param int $transactionId
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrderByTransactionId($transactionId)
    {
        $transaction = Mage::getModel('pagarme_core/transaction')
            ->load($transactionId, 'transaction_id');

        $order = Mage::getModel('sales/order')
            ->load($transaction['order_id']);

        return $order;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param Mage_Sales_Model_Order $order
     *
     * @return int $transactionId
     */
    public function getTransactionIdByOrder(Mage_Sales_Model_Order $order)
    {
        return Mage::getModel('pagarme_core/transaction')
            ->load($order->getId(), 'order_id')
            ->getTransactionId();
    }

    /**
     * @deprecated
     * @see self::getInfosRelatedByOrderId
     * @codeCoverageIgnore*
     * @param int $orderId
     * @return PagarMe_Core_Model_Transaction
     */
    public function getTransactionByOrderId($orderId)
    {
        return $this->getInfosRelatedByOrderId($orderId);
    }

    /**
     * Retrieve pagarmes' info related to an order by its id
     *
     * @codeCoverageIgnore*
     * @param int $orderId
     * @return PagarMe_Core_Model_Transaction
     */
    public function getInfosRelatedByOrderId($orderId)
    {
        return Mage::getModel('pagarme_core/transaction')
            ->load($orderId, 'order_id');
    }
}
