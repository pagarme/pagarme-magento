<?php

class PagarMe_Core_Model_System_Config_Source_PaymentMethods
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
                'value' => 'boleto',
                'label' => Mage::helper('pagarme_core')->__('Boleto Only')
            ],
            [
                'value' => 'credit_card',
                'label' => Mage::helper('pagarme_core')->__('Credit Card Only')
            ],
            [
                'value' => 'credit_card,boleto',
                'label' => Mage::helper('pagarme_core')->__('Boleto and Credit Card')
            ]
        ];
    }
}
