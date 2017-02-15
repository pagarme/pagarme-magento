<?php

namespace PagarMe\SdkTests\Recipient;

use PagarMe\Sdk\Recipient\Request\RecipientCreate;
use PagarMe\Sdk\RequestInterface;

class RecipientCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH            = 'recipients';

    const BANK_ACCOUNT_ID = 123;

    const BANK_CODE       = 237;
    const AGENCIA         = 1383;
    const CONTA           = 13399;
    const CONTA_DV        = 2;
    const DOCUMENT_NUMBER = 15344812140;
    const LEGAL_NAME      = 'João Silva';

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $bankAccountMock = $this->getMockBuilder('PagarMe\Sdk\BankAccount\BankAccount')
            ->disableOriginalConstructor()
            ->getMock();

        $recipientCreate = new RecipientCreate(
            $bankAccountMock,
            null,
            null,
            null,
            null,
            null
        );

        $this->assertEquals(self::PATH, $recipientCreate->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $bankAccountMock = $this->getMockBuilder('PagarMe\Sdk\BankAccount\BankAccount')
            ->disableOriginalConstructor()
            ->getMock();

        $recipientCreate = new RecipientCreate(
            $bankAccountMock,
            null,
            null,
            null,
            null,
            null
        );

        $this->assertEquals(RequestInterface::HTTP_POST, $recipientCreate->getMethod());
    }

    public function recipientParams()
    {
        $bankAccountMock = $this->getMockBuilder('PagarMe\Sdk\BankAccount\BankAccount')
            ->disableOriginalConstructor()
            ->getMock();

        return [
            [$bankAccountMock, 'daily', null, true, true, 30],
            [$bankAccountMock, 'weekly', 4, false, false, null],
            [$bankAccountMock, null, null, null, true, 42],
            [$bankAccountMock, 'monthly', 13, true, false, null]
        ];
    }

    /**
     * @dataProvider recipientParams
     * @test
     */
    public function mustPayloadBeCorrect(
        $bankAccount,
        $transferInterval,
        $transferDay,
        $transferEnabled,
        $automaticAnticipationEnabled,
        $anticipatableVolumePercentage
    ) {
        $recipientCreate = new RecipientCreate(
            $bankAccount,
            $transferInterval,
            $transferDay,
            $transferEnabled,
            $automaticAnticipationEnabled,
            $anticipatableVolumePercentage
        );

        $payload = $recipientCreate->getPayload();

        $this->assertEquals(
            $transferInterval,
            $payload['transfer_interval']
        );

        $this->assertEquals(
            $transferDay,
            $payload['transfer_day']
        );

        $this->assertEquals(
            $transferEnabled,
            $payload['transfer_enabled']
        );

        $this->assertEquals(
            $automaticAnticipationEnabled,
            $payload['automatic_anticipation_enabled']
        );

        $this->assertEquals(
            $anticipatableVolumePercentage,
            $payload['anticipatable_volume_percentage']
        );
    }

    /**
     * @test
     */
    public function mustContainBankAccountId()
    {
        $bankAccountMock = $this->getMockBuilder('PagarMe\Sdk\BankAccount\BankAccount')
            ->disableOriginalConstructor()
            ->getMock();

        $bankAccountMock->method('getId')->willReturn(123);

        $recipientCreate = new RecipientCreate(
            $bankAccountMock,
            null,
            null,
            null,
            null,
            null
        );

        $payload = $recipientCreate->getPayload();

        $this->assertContains('bank_account_id', array_keys($payload));
        $this->assertNotContains('bank_account', $payload);
        $this->assertEquals(self::BANK_ACCOUNT_ID, $payload['bank_account_id']);
    }

    /**
     * @test
     */
    public function mustContainBankAccountData()
    {
        $bankAccountMock = $this->getMockBuilder('PagarMe\Sdk\BankAccount\BankAccount')
            ->disableOriginalConstructor()
            ->getMock();

        $bankAccountMock->method('getBankCode')->willReturn(self::BANK_CODE);
        $bankAccountMock->method('getAgencia')->willReturn(self::AGENCIA);
        $bankAccountMock->method('getConta')->willReturn(self::CONTA);
        $bankAccountMock->method('getContaDv')->willReturn(self::CONTA_DV);
        $bankAccountMock->method('getDocumentNumber')->willReturn(self::DOCUMENT_NUMBER);
        $bankAccountMock->method('getLegalName')->willReturn(self::LEGAL_NAME);

        $recipientCreate = new RecipientCreate(
            $bankAccountMock,
            null,
            null,
            null,
            null,
            null
        );

        $payload = $recipientCreate->getPayload();

        $this->assertContains('bank_account', array_keys($payload));
        $this->assertNotContains('bank_account_id', $payload);
        $this->assertEquals(
            [
                'bank_code'       => self::BANK_CODE,
                'agencia'         => self::AGENCIA,
                'conta'           => self::CONTA,
                'conta_dv'        => self::CONTA_DV,
                'document_number' => self::DOCUMENT_NUMBER,
                'legal_name'      => self::LEGAL_NAME,
            ],
            $payload['bank_account']
        );
    }
}
