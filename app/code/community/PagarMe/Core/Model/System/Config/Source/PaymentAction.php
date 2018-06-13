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
                'value' => '1',
                'label' => 'Authorize and Capture'
            ],
            [
                'value' => '0',
                'label' => 'Authorize Only'
            ]
        ];
    }
}
