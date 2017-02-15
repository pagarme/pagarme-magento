<?php

namespace PagarMe\Sdk\Payable;

trait PayableBuilder
{
    /**
     * @param array $payableData
     * @return Payable
     */
    private function buildPayable($payableData)
    {
        $payableData->payment_date = new \DateTime($payableData->payment_date);
        $payableData->date_created = new \DateTime($payableData->date_created);

        return new Payable(get_object_vars($payableData));
    }
}
