<?php

class PagarMe_Modal_Block_Sales_RateAmount extends Mage_Core_Block_Abstract
{
    /**
     * @return $this
     */
    public function initTotals()
    {
        $rateAmount = $this->getRateAmount();

        if ($this->shouldShowTotal()) {
            $total = new Varien_Object([
                'code' => 'pagarme_modal_rate_amount',
                'field' => 'pagarme_modal_rate_amount',
                'value' => $rateAmount,
                'label' => $this->__('Installments related Interest'),
            ]);

            $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        }

        return $this;
    }

    /**
     * @return float
     */
    private function getRateAmount()
    {
        $order = $this->getReferencedOrder();

        if (!is_null($order)) {
            return Mage::getModel('pagarme_core/transaction')
                ->load($order->getId(), 'order_id')
                ->getRateAmount();
        }
    }

    private function getReferencedOrder()
    {
        return $this->getParentBlock()->getSource();
    }

    private function shouldShowTotal()
    {
        $referencedOrder = $this->getReferencedOrder();

        if (is_null($referencedOrder)) {
            return false;
        }

        $paymentIsPagarMeCreditcard = $referencedOrder->getPayment()->getMethod() ==
            PagarMe_Modal_Model_Modal::PAGARME_MODAL;

        $rateAmount = $this->getRateAmount();
        $rateAmountIsntZero = !is_null($rateAmount) && $rateAmount > 0;

        return $paymentIsPagarMeCreditcard && $rateAmountIsntZero;
    }
}
