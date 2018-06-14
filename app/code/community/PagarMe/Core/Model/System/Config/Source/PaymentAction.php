<?php

class PagarMe_Core_Model_System_Config_Source_PaymentAction
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
                'value' => 'authorize_capture',
                'label' => 'Authorize and Capture'
            ],
            [
                'value' => 'authorize_only',
                'label' => 'Authorize Only'
            ]
        ];
    }
}
