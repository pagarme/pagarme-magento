<?php

class PagarMe_V2_Core_Model_System_Config_Source_CaptureCustomerData
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
                'value' => 'true',
                'label' => 'Sim'
            ],
            [
                'value' => 'false',
                'label' => 'Não'
            ]
        ];
    }
}
