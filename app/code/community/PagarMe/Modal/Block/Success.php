<?php

class PagarMe_Modal_Block_Success extends Mage_Checkout_Block_Onepage_Success
{
    /**
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /**
     * @codeCoverageIgnore
     */
    public function getOrder()
    {
        if (is_null($this->order)) {
            $this->order = Mage::getModel('sales/order')->loadByIncrementId(
                $this->getOrderId()
            );
        }

        return $this->order;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setOrder(Mage_Sales_Model_Order $order)
    {
        $this->order = $order;
    }


    /**
     * @return bool
     */
    public function isBoletoPayment()
    {
        $order = $this->getOrder();

        $additionalInfo = $order->getPayment()->getAdditionalInformation();
        $paymentMethod = null;
        if(array_key_exists('pagarme_payment_method', $additionalInfo)) {
            $paymentMethod = $additionalInfo['pagarme_payment_method'];
        }
        if ($paymentMethod === PagarMe_Modal_Model_Modal::PAGARME_MODAL_BOLETO) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getBoletoUrl()
    {
        $order = $this->getOrder();

        $additionalInfo = $order->getPayment()->getAdditionalInformation();

        return $additionalInfo['pagarme_boleto_url'];
    }
}
