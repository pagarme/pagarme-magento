<?php

class PagarMe_CreditCard_Model_CreditmemoTotals extends  Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{

    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        $transaction = \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $order->getId()
            );
        $creditmemo->setGrandTotal($transaction->getRateAmount());
        $creditmemo->setBaseGrandTotal($transaction->getRateAmount());

        return $this;
    }

}
