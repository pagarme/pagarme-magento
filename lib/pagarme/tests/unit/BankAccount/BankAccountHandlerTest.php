<?php

namespace Pagarme\SdkTests\BankAccount;

use PagarMe\Sdk\BankAccount\BankAccountHandler;

class BankAccountTest extends \PHPUnit_Framework_TestCase
{
    const BANK_ACCOUNT_ID = 4840;

    /**
     * @test
     */
    public function mustReturnBankAccount()
    {
        $clientMock =  $this->getMockBuilder('PagarMe\Sdk\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('send')
            ->willReturn(json_decode($this->getBankAccountResponse()));

        $handler = new BankAccountHandler($clientMock);

        $this->assertInstanceOf(
            'Pagarme\Sdk\BankAccount\BankAccount',
            $handler->create(
                341,
                932,
                58054,
                1,
                'API BANK ACCOUNT',
                26268738888,
                5
            )
        );
    }

    /**
     * @test
     */
    public function mustReturnBankAccountArray()
    {
        $clientMock =  $this->getMockBuilder('PagarMe\Sdk\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('send')
            ->willReturn(json_decode($this->getBankAccountListResponse()));

        $handler = new BankAccountHandler($clientMock);

        $bankAccounts = $handler->getList();

        $this->assertContainsOnly(
            'Pagarme\Sdk\BankAccount\BankAccount',
            $bankAccounts
        );
        $this->assertEquals(3, count($bankAccounts));
    }

    /**
     * @test
     */
    public function mustObtainBankAccount()
    {
        $clientMock =  $this->getMockBuilder('PagarMe\Sdk\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $clientMock->method('send')
            ->willReturn(json_decode($this->getBankAccountResponse()));

        $handler = new BankAccountHandler($clientMock);

        $bankAccounts = $handler->get(self::BANK_ACCOUNT_ID);

        $this->assertInstanceOf(
            'Pagarme\Sdk\BankAccount\BankAccount',
            $bankAccounts
        );
    }

    private function getBankAccountResponse()
    {
        return '{"object": "bank_account", "id": 4840, "bank_code": "341", "agencia": "0932", "agencia_dv": "5", "conta": "58054", "conta_dv": "1", "document_type": "cpf", "document_number": "26268738888", "legal_name": "API BANK ACCOUNT", "charge_transfer_fees": false, "date_created": "2015-03-19T15:35:40.000Z"}';
    }

    private function getBankAccountListResponse()
    {
        return sprintf(
            '[%s, %s, %s]',
            $this->getBankAccountResponse(),
            $this->getBankAccountResponse(),
            $this->getBankAccountResponse()
        );
    }
}
