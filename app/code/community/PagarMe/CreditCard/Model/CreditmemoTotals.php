<?php

class PagarMe_CreditCard_Model_CreditmemoTotals extends  Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     *
     * @return PagarMe_CreditCard_Model_CreditmemoTotals
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        $transaction = \Mage::getModel('pagarme_core/service_order')
            ->getTransactionByOrderId(
                $order->getId()
            );
        $creditmemo->setGrandTotal(
            $creditmemo->getGrandTotal() + $transaction->getRateAmount()
        );
        $creditmemo->setBaseGrandTotal(
            $creditmemo->getBaseGrandTotal() + $transaction->getRateAmount()
        );

        return $this;
    }
}
