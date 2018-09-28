<?php

class PagarMe_Core_Model_Service_Transaction
{
    /**
     * @var \PagarMe\Sdk\PagarMe
     */
    protected $pagarMeSdk;
    
    /**
     * @return \PagarMe\Sdk\PagarMe
     */
    public function getPagarMeSdk()
    {
        if (is_null($this->pagarMeSdk)) {
            $this->setPagarMeSdk(
                Mage::getModel('pagarme_core/sdk_adapter')
                    ->getPagarMeSdk()
            );
        }

        return $this->pagarMeSdk;
    }

    /**
     * @param \PagarMe\Sdk\PagarMe $pagarMeSdk
     *
     * @return void
     */
    public function setPagarMeSdk(\PagarMe\Sdk\PagarMe $pagarMeSdk)
    {
        $this->pagarMeSdk = $pagarMeSdk;
    }

    /**
     * @param int $transactionId
     * @return \PagarMe\Sdk\Transaction\AbstractTransaction
     */
    public function getTransactionById($transactionId)
    {
        return $this
            ->getPagarMeSdk()
            ->transaction()
            ->get($transactionId);
    }

    /**
     * @param \PagarMe\Sdk\Transaction\AbstractTransaction $transaction
     *
     * @return \PagarMe\Sdk\Transaction\AbstractTransaction
     *
     * @throws Exception
     */
    public function capture(
        \PagarMe\Sdk\Transaction\AbstractTransaction $transaction
    ) {
        try {
            return $this->getPagarMeSdk()
                ->transaction()
                ->capture(
                    $transaction,
                    $transaction->getAmount()
                );
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
