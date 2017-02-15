<?php

namespace PagarMe\Sdk\BalanceOperation;

trait OperationBuilder
{
    use \PagarMe\Sdk\Payable\PayableBuilder;
    use \PagarMe\Sdk\Transfer\TransferBuilder;

    /**
     * @param \stdClass $operationData
     * @return Operation
     */
    private function buildOperation(\stdClass $operationData)
    {
        $operationData->movement = $this->buildMovement(
            $operationData->movement_object
        );
        $operationData->date_created = new \DateTime(
            $operationData->date_created
        );
        return new Operation(get_object_vars($operationData));
    }

    /**
     * @param \stdClass $movementData
     * @return \PagarMe\Sdk\Payable\Payable | \PagarMe\Sdk\Transfer\Transfer
     */
    private function buildMovement(\stdClass $movementData)
    {
        if ($movementData->object == 'payable') {
            return $this->buildPayable($movementData);
        }

        if ($movementData->object == 'transfer') {
            return $this->buildTransfer($movementData);
        }

        throw new \Exception(
            sprintf(
                "Unknow movement type supplied: %s",
                $movementData->object
            ),
            1
        );
    }
}
