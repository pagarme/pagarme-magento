<?php

class PagarMe_Core_Model_System_Config_Source_PaymentAction
{
    const AUTH_ONLY = 'authorize_only';
    const AUTH_CAPTURE = 'authorize_capture';

    /**
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::AUTH_CAPTURE,
                'label' => Mage::helper('pagarme_core')
                    ->__('Authorize and Capture')
            ],
            [
                'value' => self::AUTH_ONLY,
                'label' => Mage::helper('pagarme_core')
                    ->__('Authorize Only')
            ]
        ];
    }
}
