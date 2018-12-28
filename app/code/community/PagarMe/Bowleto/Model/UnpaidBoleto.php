<?php

use \PagarMe\Sdk\Transaction\BoletoTransaction;

class PagarMe_Bowleto_Model_UnpaidBoleto
{
    /**
     * Returns configured timezone on platform
     *
     * @return string
     */
    private function getCurrentTimezone()
    {
        return Mage::getStoreConfig('general/locale/timezone');
    }

    /**
     * Filter expired boletos
     *
     * @return Varien_Object
     */
    private function expiredBoletos()
    {
        $today = new DateTime(
            'now',
            new DateTimeZone($this->getCurrentTimezone())
        );
        $expiredBoletos = $today->modify('-7 days');

        $boletosFilter = Mage::getModel('pagarme_core/transaction')
            ->getCollection();

        $boletosFilter->addFieldToFilter(
            'boleto_expiration_date',
            ['lt' => $expiredBoletos->format('Y-m-d H:i:s')]
        );

        return $boletosFilter->getItems();
    }

    /**
     * Retrieve an order
     *
     * @param int $orderId
     *
     * @return Mage_Sales_Model_Order
     */
    private function loadOrder($orderId)
    {
        return Mage::getModel('sales/order')
            ->load($orderId);
    }

    /**
     * @param int $transactionId
     * @return BoletoTransaction
     */
    private function loadBoletoTransaction($transactionId)
    {
        $sdk = Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk();

        return $sdk->transaction()->get($transactionId);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param BoletoTransaction $boletoTransaction
     */
    private function cancelOrder(
        Mage_Sales_Model_Order $order,
        BoletoTransaction $boletoTransaction
    ) {
        if (
            $order->getState() ===
            Mage_Sales_Model_Order::STATE_PENDING_PAYMENT
        ) {
            $cancelHandler = new PagarMe_Core_Model_OrderStatusHandler_UnpaidBoleto(
                $order,
                $boletoTransaction
            );

            $cancelHandler->handleStatus();
        }
    }

    /**
     * Cancel orders from unpaid boleto
     *
     * @return void
     */
    public function cancel()
    {
        $expiredBoletos = $this->expiredBoletos();

        foreach ($expiredBoletos as $expiredBoleto) {
            try {
                $order = $this->loadOrder($expiredBoleto->getOrderId());
                $transaction = $this->loadBoletoTransaction(
                    $expiredBoleto->getTransactionId()
                );

                $this->cancelOrder($order, $transaction);
            } catch (\Exception $exception) {
                $logMessage = sprintf(
                    'Error canceling unpaid boleto order, id: %s, message: %s',
                    $expiredBoleto->getOrderId(),
                    $exception->getMessage()
                );

                Mage::log($logMessage);

                continue;
            }
        }
    }
}
