<?php

class PagarMe_Core_Model_System_Config_Source_BoletoDiscountMode
{
    const NO_DISCOUNT = 'no_discount';
    const FIXED_VALUE = 'fixed_value';
    const PERCENTAGE = 'percentage';

    /**
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            self::NO_DISCOUNT => 'No discount',
            self::FIXED_VALUE => 'Fixed value',
            self::PERCENTAGE => 'Percentage'
        ];
    }
}
