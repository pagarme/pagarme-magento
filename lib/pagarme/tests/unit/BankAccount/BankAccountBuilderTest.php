<?php

namespace PagarMe\SdkTest\BankAccount;

class BankAccountBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\BankAccount\BankAccountBuilder;

    /**
    * @test
    */
    public function mustCreateBankAccountCorrectly()
    {
        $payload = '{"object":"bank_account","id":17359980,"bank_code":"341","agencia":"0932","agencia_dv":null,"conta":"58098","conta_dv":"5","type":"conta_corrente","document_type":"cpf","document_number":"26268738888","legal_name":"API BANK ACCOUNT","charge_transfer_fees":true,"date_created":"2017-01-04T16:09:06.778Z"}';

        $bankAccount = $this->buildBankAccount(json_decode($payload));

        $this->assertInstanceOf(
            'PagarMe\Sdk\BankAccount\BankAccount',
            $bankAccount
        );
        $this->assertInstanceOf('\DateTime', $bankAccount->getDateCreated());
    }
}
