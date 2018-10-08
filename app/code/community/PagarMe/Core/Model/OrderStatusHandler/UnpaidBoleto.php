<?php

use PagarMe_Core_Model_OrderStatusHandler_Base as BaseHandler;

class PagarMe_Core_Model_OrderStatusHandler_UnpaidBoleto extends BaseHandler
{
    /**
     * @return string
     */
    private function buildCancelMessage()
    {
        $message = sprintf(
            'Canceled due unpaid boleto. Expiration date was %s',
            $this->transaction->getBoletoExpirationDate()->format('d/m/Y')
        );

        return Mage::helper('pagarme_core')
            ->__($message);
    }

    /**
     * Responsible to handle order status based on transaction status
     */
    public function handleStatus()
    {
        $canceledHandler = new PagarMe_Core_Model_OrderStatusHandler_Canceled(
            $this->order,
            $this->transaction,
            $this->buildCancelMessage()
        );

        $canceledHandler->handleStatus();

        return $this->order;
    }
}
