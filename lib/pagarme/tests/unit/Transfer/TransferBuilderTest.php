<?php

namespace PagarMe\SdkTest\Transfer;

class TransferGetTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Transfer\TransferBuilder;
    /**
     * @test
     */
    public function mustTransferBeCreatedCorrectly()
    {
        $payload = '{"object": "transfer", "id": 22180, "amount": 3612, "type": "credito_em_conta", "status": "pending_transfer", "source_type": "recipient", "source_id": "re_cix9cglnu018r9k6ec57ocmse", "target_type": "bank_account", "target_id": "17322882", "fee": 0, "funding_date": null, "funding_estimated_date": "2016-12-29T02:00:00.000Z", "transaction_id": null, "date_created": "2016-12-28T19:38:17.439Z", "bank_account": {"object": "bank_account", "id": 17322882, "bank_code": "237", "agencia": "1935", "agencia_dv": null, "conta": "060708", "conta_dv": "1", "type": "conta_corrente", "document_type": "cpf", "document_number": "20487713435", "legal_name": "Joao Silva", "charge_transfer_fees": true, "date_created": "2016-12-13T12:42:54.948Z"}}';

        $transfer = $this->buildTransfer(json_decode($payload));

        $this->assertInstanceOf('PagarMe\Sdk\Transfer\Transfer', $transfer);
        $this->assertInstanceOf(
            '\DateTime',
            $transfer->getFundingEstimatedDate()
        );
        $this->assertInstanceOf('\DateTime', $transfer->getDateCreated());
        $this->assertInstanceOf('\DateTime', $transfer->getFundingDate());
        $this->assertInstanceOf(
            'PagarMe\Sdk\BankAccount\BankAccount',
            $transfer->getBankAccount()
        );
    }
}
