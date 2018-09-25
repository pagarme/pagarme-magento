<?php
use PagarMe\Sdk\Transaction\AbstractTransaction;

trait PagarMe_Core_Block_Info_Trait
{
    /**
     * @codeCoverageIgnore
     *
     * @return AbstractTransaction
     * @throws \Exception
     */
    public function getTransaction()
    {
        if (!is_null($this->transaction)) {
            return $this->transaction;
        }

        $transactionId = $this->getTransactionIdFromDb();
        $this->transaction = $this->fetchPagarmeTransactionFromAPi(
            $transactionId
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

        $pagarmeInfosRelated = \Mage::getModel('pagarme_core/service_order')
            ->getInfosRelatedByOrderId(
                $order->getId()
            );

        return $pagarmeInfosRelated->getTransactionId();
    }

    /**
     * Fetch transaction's information from API
     *
     * @param int $transactionId
     *
     * @return AbstractTransaction
     */
    private function fetchPagarmeTransactionFromAPi($transactionId)
    {
        return \Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk()
            ->transaction()
            ->get($transactionId);
    }
}
