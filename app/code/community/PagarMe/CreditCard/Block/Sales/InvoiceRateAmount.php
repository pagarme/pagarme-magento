<?php

class PagarMe_CreditCard_Block_Sales_InvoiceRateAmount extends PagarMe_CreditCard_Block_Sales_RateAmount
{
    protected function getReferencedOrder()
    {
        $invoice = parent::getReferencedOrder();
        return $invoice->getOrder();
    }
}
