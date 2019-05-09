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
                'value' => 'pagarme_bowleto',
                'label' => Mage::helper('pagarme_core')->__('Boleto Only')
            ],
            [
                'value' => 'pagarme_creditcard',
                'label' => Mage::helper('pagarme_core')->__('Credit Card Only')
            ],
            [
                'value' => 'pagarme_creditcard,pagarme_bowleto',
                'label' => Mage::helper('pagarme_core')->__(
                    'Boleto and Credit Card'
                )
            ]
        ];
    }
}
