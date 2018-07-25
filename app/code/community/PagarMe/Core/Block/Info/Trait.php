<?php

trait PagarMe_Core_Block_Info_Trait
{
    /**
     * @var \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    private $transaction;

    /**
     * @codeCoverageIgnore
     *
     * @return PagarMe\Sdk\Transaction\AbstractTransaction
     * @throws \Exception
     */
    public function getTransaction()
    {
        if (!is_null($this->transaction)) {
            return $this->transaction;
        }

        $pagarmeDbTransaction = $this->getTransactionIdFromDb();
        $this->transaction = $this
            ->fetchPagarmeTransactionFromAPi(
                $pagarmeDbTransaction->getTransactionId()
            );

        return $this->transaction;
    }

    /**
     * Retrieve transaction_id from database
     *
     * @return int
     * @throws \Exception
     */
    private function getTransactionIdFromDb()
    {
        $order = $this->getInfo()->getOrder();

        if (is_null($order)) {
            throw new \Exception('Order doesn\'t exist');
        }

        return \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $order->getId()
            );
    }

    /**
     * Fetch transaction's information from API
     *
     * @param int $transactionId
     * @return \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    private function fetchPagarmeTransactionFromAPi($transactionId)
    {
        return \Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk()
            ->transaction()
            ->get($transactionId);
    }
}