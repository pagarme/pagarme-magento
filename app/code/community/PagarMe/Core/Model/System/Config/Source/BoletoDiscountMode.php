<?php

class PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode
{
    const NO_DISCOUNT = 'no_discount';
    const FIXED_VALUE = 'fixed_value';
    const PERCENTAGE = 'percentage';

    /**
     * @var PagarMe_Core_Helper_Data
     */
    protected $pagarmeModalHelper;

    /**
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function toOptionArray()
    {
        $pagarmeModalHelper = Mage::helper('pagarme_modal');

        return [
            self::NO_DISCOUNT => $pagarmeModalHelper->__('No discount'),
            self::FIXED_VALUE => $pagarmeModalHelper->__('Fixed value'),
            self::PERCENTAGE => $pagarmeModalHelper->__('Percentage')
        ];
    }
}
