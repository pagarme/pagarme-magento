<?php

namespace Pagarme\SdkTests\Recipient;

class RecipientBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Recipient\RecipientBuilder;

    /**
     * @test
     */
    public function mustRecipientBeCreatedCorrectly()
    {
        $recipient = $this->buildRecipient(json_decode('{"object":"recipient","id":"re_cixj52kzw01cn0a6dntrxl5au","transfer_enabled":false,"last_transfer":null,"transfer_interval":"daily","transfer_day":null,"automatic_anticipation_enabled":false,"anticipatable_volume_percentage":0,"date_created":"2017-01-04T16:09:06.797Z","date_updated":"2017-01-04T16:09:06.797Z","bank_account":{"object":"bank_account","id":17359980,"bank_code":"341","agencia":"0932","agencia_dv":null,"conta":"58098","conta_dv":"5","type":"conta_corrente","document_type":"cpf","document_number":"26268738888","legal_name":"API BANK ACCOUNT","charge_transfer_fees":true,"date_created":"2017-01-04T16:09:06.778Z"}}'));

        $this->assertInstanceOf('PagarMe\Sdk\Recipient\Recipient', $recipient);
        $this->assertInstanceOf('\DateTime', $recipient->getDateCreated());
        $this->assertInstanceOf('\DateTime', $recipient->getDateUpdated());
        $this->assertInstanceOf(
            'PagarMe\Sdk\BankAccount\BankAccount',
            $recipient->getBankAccount()
        );
    }
}
