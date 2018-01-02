<?php

class PagarMe_Modal_Block_Sales_RateAmount extends Mage_Core_Block_Abstract
{
    /**
     * @return float
     */
    private function getRateAmount()
    {
        $order = $this->getParentBlock()->getSource();

        if (!is_null($order)) {
            return Mage::getModel('pagarme_core/transaction')
                ->load($order->getId(), 'order_id')
                ->getRateAmount();
        }
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $rateAmount = $this->getRateAmount();

        if (!is_null($rateAmount) && $rateAmount > 0) {
            $total = new Varien_Object([
                'code' => 'pagarme_modal_rate_amount',
                'field' => 'pagarme_modal_rate_amount',
                'value' => $rateAmount,
                'label' => 'Interest Fee',
            ]);

            $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        }

        return $this;
    }
}
