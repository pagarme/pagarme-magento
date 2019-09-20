<?php

class PagarMe_CreditCard_Block_Sales_RateAmount extends Mage_Core_Block_Abstract
{
    /**
     * @return $this
     */
    public function initTotals()
    {
        if ($this->shouldShowTotal()) {
            $total = new Varien_Object([
                'code' => 'pagarme_creditcard_rate_amount',
                'field' => 'pagarme_creditcard_rate_amount',
                'value' => $this->getRateAmount(),
                'label' => __('Installments related Interest'),
            ]);

            $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        }

        return $this;
    }

    /**
     * @return float
     */
    protected function getRateAmount()
    {
        $order = $this->getReferencedOrder();

        if (!is_null($order)) {
            return Mage::getModel('pagarme_core/transaction')
                ->load($order->getId(), 'order_id')
                ->getRateAmount();
        }
    }

    protected function getReferencedOrder()
    {
        return $this->getParentBlock()->getSource();
    }

    protected function shouldShowTotal()
    {
        $referencedOrder = $this->getReferencedOrder();

        if (is_null($referencedOrder)) {
            return false;
        }

        $paymentIsPagarMeCreditcard = $referencedOrder->getPayment()->getMethod() ==
            PagarMe_CreditCard_Model_Creditcard::PAGARME_CREDITCARD;

        $rateAmount = $this->getRateAmount();
        $rateAmountIsntZero = !is_null($rateAmount) && $rateAmount > 0;

        return $paymentIsPagarMeCreditcard && $rateAmountIsntZero;
    }
}
