<?php

class PagarMe_Core_Model_Service_Invoice extends Mage_Core_Model_Abstract
{
    /**
     * @codeCoverageIgnore
     *
     * @param type $order
     *
     * @return type
     */
    public function createInvoiceFromOrder($order)
    {
        return Mage::getModel('sales/service_order', $order)
            ->prepareInvoice();
    }
}
