<?php

class PagarMe_V2_Core_Model_Quote_Address_Total_CreditCardInterestAmount
 extends PagarMe_V2_Core_Model_Quote_Address_Total_Abstract
{
    private $interestAmount;

    public function __construct()
    {
        $this->setCode('pagarme_v2_checkout_credit_card');
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('pagarme_v2_checkout')->__('Interest fee');
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @return PagarMe_V2_Checkout_Model_Total
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $address->setDiscountAmount(0);
        $address->setBaseDiscountAmount(0);

        if (!$this->shouldCollect()) {
            return $this;
        }
                
        $quote = $address->getQuote();
        $subtotalAmount = $this->getSubtotal($quote);
        
        $transaction = $this->getTransaction();
        $totalAmount = Mage::helper('pagarme_v2_core')
            ->parseAmountToFloat($transaction->getAmount());
        
        $this->interestAmount = $totalAmount - $subtotalAmount;
        
        if ($this->interestAmount > 0) {
            $this->_addAmount($this->interestAmount);
            $this->_addBaseAmount($this->interestAmount);
        }
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldCollect()
    {
        if (!parent::shouldCollect()) {
            return false;
        }

        $transaction = $this->getTransaction();

        if (!$transaction instanceof \PagarMe\Sdk\Transaction\CreditCardTransaction) {
            return false;
        }

        if ($this->interestAmount > 0) {
            return false;
        }

        return true;
    }

    /**
     * Add giftcard totals information to address object
     *
     * @param Mage_Sales_Model_Quote_Address $address
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $addressTotalAmount = $address->getTotalAmount($this->getCode());

        if ($this->interestAmount != 0 && $addressTotalAmount == 0) {
            $address->addTotal(array(
                'code'  => $this->getCode(),
                'title' => $this->getLabel(),
                'value' => $this->interestAmount
            ));
        }

        return $this;
    }
}
