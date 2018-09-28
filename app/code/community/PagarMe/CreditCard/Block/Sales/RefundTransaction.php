<?php

class PagarMe_CreditCard_Block_Sales_RefundTransaction extends Mage_Core_Block_Abstract
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

            $this->getParentBlock()->addTotal($total, 'creditmemo_totals');
        }

        return $this;
    }

    /**
     * @return float
     */
    protected function getRateAmount()
    {
        $order = $this->getReferencedOrder();

        return (float)Mage::getModel('pagarme_core/transaction')
            ->load($order->getId(), 'order_id')
            ->getRateAmount();
    }

    protected function getReferencedOrder()
    {
        return $this->getParentBlock()->getSource()->getOrder();
    }

    protected function checkIfPaymentMethodIsCC()
    {
        $ccMethod = PagarMe_CreditCard_Model_Creditcard::PAGARME_CREDITCARD;
        $orderPaymentMethod = $this->getReferencedOrder()
                                    ->getPayment()
                                    ->getMethod();

        return $orderPaymentMethod == $ccMethod;
    }

    protected function shouldShowTotal()
    {
        $paymentIsPagarMeCreditcard = $this->checkIfPaymentMethodIsCC();

        $rateAmount = $this->getRateAmount();
        $rateAmountIsntZero = !is_null($rateAmount) && $rateAmount > 0;

        return $paymentIsPagarMeCreditcard && $rateAmountIsntZero;
    }
}
