<?php

class PagarMe_Core_Model_System_Config_Source_CreditCardBrands
{
    /**
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'visa',
                'label' => 'Visa'
            ],
            [
                'value' => 'mastercard',
                'label' => 'Mastercard'
            ],
            [
                'value' => 'amex',
                'label' => 'Amex'
            ],
            [
                'value' => 'hipercard',
                'label' => 'Hipercard'
            ],
            [
                'value' => 'aura',
                'label' => 'Aura'
            ],
            [
                'value' => 'jcb',
                'label' => 'JCB'
            ],
            [
                'value' => 'diners',
                'label' => 'Diners'
            ],
            [
                'value' => 'elo',
                'label' => 'Elo'
            ],
            [
                'value' => 'discover',
                'label' => 'Discover'
            ],
        ];
    }
}
