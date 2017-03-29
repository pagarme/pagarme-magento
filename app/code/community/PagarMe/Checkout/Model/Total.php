<?php
class PagarMe_Checkout_Model_Total extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    private $interestAmount;

    public function __construct()
    {
        $this->setCode('pagarme_checkout');
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('pagarme_checkout')->__('Interest/Discount');
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $address
     * @return PagarMe_Checkout_Model_Total
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $paymentData = Mage::app()->getRequest()->getPost('payment');

        if (is_null($paymentData)) {
            return $this;
        }

        if ($this->interestAmount != 0) {
            return $this;
        }

        $transaction = Mage::getModel(
            'pagarme_core/sdk_adapter'
            )->getPagarMeSdk()
            ->transaction()
            ->get($paymentData['pagarme_checkout_token']);

        $quote = $address->getQuote();

        $quoteTotals = $quote->getTotals();
        $baseSubtotalWithDiscount = $quoteTotals['subtotal']->getValue();

        $shippingAmount = $quote->getShippingAddress()->getShippingAmount();

        $subTotal = $baseSubtotalWithDiscount + $shippingAmount;

        $totalAmount = $transaction->getAmount()/100;
        $this->interestAmount = $totalAmount - $subTotal;

        if ($this->interestAmount) {
            $this->_addAmount($this->interestAmount);
            $this->_addBaseAmount($this->interestAmount);
        }

        return $this;
    }

    /**
     * Add giftcard totals information to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
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
