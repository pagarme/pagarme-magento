<?php

class PagarMe_Boleto_Block_Success extends Mage_Checkout_Block_Onepage_Success
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
        $paymentMethod = (array_key_exists('pagarme_payment_method', $additionalInfo)) ? $additionalInfo['pagarme_payment_method'] : null; 
        $isBoleto = $paymentMethod === PagarMe_Boleto_Model_Boleto::PAGARME_BOLETO;
        if ($isBoleto) {
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
