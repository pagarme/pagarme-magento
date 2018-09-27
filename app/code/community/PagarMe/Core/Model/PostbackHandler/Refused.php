<?php

use \PagarMe\Sdk\Transaction\AbstractTransaction;

class PagarMe_Core_Model_PostbackHandler_Refused extends PagarMe_Core_Model_PostbackHandler_Base
{
    const MAGENTO_DESIRED_STATE = Mage_Sales_Model_Order::STATE_CANCELED;

    /**
     * Returns the desired state on magento
     * @deprecated
     *
     * @see    PagarMe_Core_Model_OrderStatusHandler_Canceled
     * @return string
     */
    protected function getDesiredState()
    {
        return self::MAGENTO_DESIRED_STATE;
    }

    /**
     * @return AbstractTransaction
     */
    private function retrieveTransaction()
    {
        $sdk = Mage::getModel('pagarme_core/sdk_adapter')
            ->getPagarMeSdk();

        return $sdk->transaction()->get($this->transactionId);
    }

    /**
     * @return \Mage_Sales_Model_Order
     */
    public function process()
    {
        $transaction = $this->retrieveTransaction();

        $canceledHandler = new PagarMe_Core_Model_OrderStatusHandler_Canceled(
            $this->order,
            $transaction,
            $this->buildRefusedReasonMessage($transaction->getRefuseReason())
        );
        $canceledHandler->handleStatus();

        return $this->order;
    }

    /**
     * Returns refuse message sent by Pagar.me API
     *
     * @param string $refuseReason
     *
     * @return string
     */
    private function buildRefusedReasonMessage($refuseReason)
    {
        return sprintf(
            'Refused by %s',
            $refuseReason
        );
    }
}
