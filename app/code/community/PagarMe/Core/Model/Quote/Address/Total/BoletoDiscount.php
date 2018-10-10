<?php

class PagarMe_Core_Model_Quote_Address_Total_BoletoDiscount extends PagarMe_Core_Model_Quote_Address_Total_Abstract
{
    private $discount;

    public function __construct()
    {
        $this->setCode('pagarme_modal_boleto');
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('pagarme_modal')->__('Discount');
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @return PagarMe_Modal_Model_Total
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        if (!$this->shouldCollect()) {
            return $this;
        }

        $quote = $address->getQuote();
        $subtotalAmount = $this->getSubtotal($quote);

        $transaction = $this->getTransaction();
        $totalAmount = Mage::helper('pagarme_core')
            ->parseAmountToCurrency($transaction->getAmount());

        $this->discount = $totalAmount - $subtotalAmount;

        if ($address->getGrandTotal() == 0) {
            $this->_setAddress($address);
            $this->_setAmount($this->discount);
            $this->_setBaseAmount($subtotalAmount);

            $quote->setGrandTotal($this->discount);
            $quote->setBaseGrandTotal($subtotalAmount);

            $quote->save();
            $address->setQuote($quote);

            $address->setDiscountAmount($this->discount);
            $address->setBaseDiscountAmount($this->discount);
            $address->save();
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function hasDiscountOnBoleto()
    {
        return (Mage::getStoreConfig('payment/pagarme_configurations/boleto_discount_mode')
            == PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode::FIXED_VALUE) ||
            (Mage::getStoreConfig('payment/pagarme_configurations/boleto_discount_mode')
            == PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode::PERCENTAGE);
    }

    /**
     * @return bool
     */
    protected function shouldCollect()
    {
        if (!parent::shouldCollect()) {
            return false;
        }

        $transaction = $this->getTransaction();

        if (!$transaction instanceof \PagarMe\Sdk\Transaction\BoletoTransaction) {
            return false;
        }

        if (!$this->hasDiscountOnBoleto()) {
            return false;
        }

        if ($this->discount < 0) {
            return false;
        }

        return true;
    }
}
