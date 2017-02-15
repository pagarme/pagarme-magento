<?php

namespace PagarMe\Sdk\Calculation;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Calculation\Request\CalculateInstallmentsRequest;

class CalculationHandler extends AbstractHandler
{
    /**
     * @param int $amount
     * @param int $interestRate
     * @param int $freeInstallments
     * @param int $maxInstallments
     * @return array
     */
    public function calculateInstallmentsAmount(
        $amount,
        $interestRate,
        $freeInstallments = 1,
        $maxInstallments = 12
    ) {
        $request = new CalculateInstallmentsRequest(
            $amount,
            $interestRate,
            $freeInstallments,
            $maxInstallments
        );

        $response = $this->client->send($request);

        $installments = [];
        foreach ($response->installments as $key => $value) {
            $installments[$key] = [
                'installment_amount' => $value->installment_amount,
                'total_amount' => $value->amount
            ];
        }

        return $installments;
    }
}
