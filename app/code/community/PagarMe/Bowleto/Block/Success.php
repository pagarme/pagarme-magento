<?php

class PagarMe_Bowleto_Block_Success extends Mage_Checkout_Block_Onepage_Success
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

        if ($paymentMethod === PagarMe_Bowleto_Model_Boleto::PAGARME_BOLETO) {
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
