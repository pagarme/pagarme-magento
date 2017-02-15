<?php

namespace PagarMe\SdkTest\BalanceOperation;

class OperationBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\BalanceOperation\OperationBuilder;

    /**
     * @test
     */
    public function mustCreateOperationWithTransferCorreclty()
    {
        $payload = '{"object":"balance_operation","id":593829,"status":"available","balance_amount":0,"balance_old_amount":null,"type":"transfer","amount":-4162,"fee":0,"date_created":"2016-12-28T19:38:16.252Z","movement_object":{"object":"transfer","id":22179,"amount":4162,"type":"credito_em_conta","status":"pending_transfer","source_type":"recipient","source_id":"re_cix9cgkj701cfy36dr3tj0smx","target_type":"bank_account","target_id":"17322882","fee":0,"funding_date":null,"funding_estimated_date":"2016-12-29T02:00:00.000Z","transaction_id":null,"date_created":"2016-12-28T19:38:15.862Z","bank_account":{"object":"bank_account","id":17322882,"bank_code":"237","agencia":"1935","agencia_dv":null,"conta":"060708","conta_dv":"1","type":"conta_corrente","document_type":"cpf","document_number":"20487713435","legal_name":"Joao Silva","charge_transfer_fees":true,"date_created":"2016-12-13T12:42:54.948Z"}}}';

        $operation = $this->buildOperation(json_decode($payload));
        $this->assertInstanceOf(
            'PagarMe\Sdk\BalanceOperation\Operation',
            $operation
        );
        $this->assertInstanceOf(
            'PagarMe\Sdk\Transfer\Transfer',
            $operation->getMovement()
        );
        $this->assertInstanceOf('\DateTime', $operation->getDateCreated());
    }

    /**
     * @test
     */
    public function mustCreateOperationWithPayableCorreclty()
    {
        $payload = '{"object":"balance_operation","id":593849,"status":"available","balance_amount":0,"balance_old_amount":null,"type":"payable","amount":50000,"fee":190,"date_created":"2016-12-28T19:38:17.171Z","movement_object":{"object":"payable","id":441944,"status":"paid","amount":50000,"fee":190,"anticipation_fee":0,"installment":null,"transaction_id":994229,"split_rule_id":"sr_cix9cgluu018s9k6estsuiqh7","bulk_anticipation_id":null,"recipient_id":"re_cix9cglnu018r9k6ec57ocmse","payment_date":"2016-12-28T02:00:00.994Z","original_payment_date":null,"type":"credit","payment_method":"boleto","date_created":"2016-12-28T19:38:17.079Z"}}';

        $operation = $this->buildOperation(json_decode($payload));

        $this->assertInstanceOf(
            'PagarMe\Sdk\BalanceOperation\Operation',
            $operation
        );
        $this->assertInstanceOf(
            'PagarMe\Sdk\Payable\Payable',
            $operation->getMovement()
        );
        $this->assertInstanceOf('\DateTime', $operation->getDateCreated());
    }
}
