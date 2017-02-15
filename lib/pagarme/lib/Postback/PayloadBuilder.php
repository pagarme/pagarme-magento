<?php

namespace PagarMe\Sdk\Postback;

trait PayloadBuilder
{
    use \PagarMe\Sdk\Transaction\TransactionBuilder;

    /**
     * @param array $payloadData
     * @return Payload
     */
    private function buildPayload($payloadData)
    {
        parse_str($payloadData, $payload);

        $payload['transaction'] = $this->buildTransaction(
            (object) $payload['transaction']
        );

        return new Payload($payload);
    }
}
