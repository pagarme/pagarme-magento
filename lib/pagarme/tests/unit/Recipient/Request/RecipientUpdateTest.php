<?php

namespace PagarMe\SdkTests\Recipient;

use PagarMe\Sdk\Recipient\Request\RecipientUpdate;
use PagarMe\Sdk\RequestInterface;

class RecipientUpdateTest extends \PHPUnit_Framework_TestCase
{
    const PATH         = 'recipients/re_x1y2z3';
    const RECIPIENT_ID = 're_x1y2z3';

    const BANK_ACCOUNT_ID = 123;

    const TRANSFER_INTERVAL               = 'weekly';
    const TRANSFER_DAY                    = 2;
    const TRANSFER_ENABLED                = false;
    const AUTOMATIC_ANTICIPATION_ENABLED  = false;
    const ANTICIPATABLE_VOLUME_PERCENTAGE = 42;

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
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();

        $recipientMock->method('getId')->willReturn(self::RECIPIENT_ID);

        $recipientUpdate = new RecipientUpdate($recipientMock);

        $this->assertEquals(self::PATH, $recipientUpdate->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();

        $recipientUpdate = new RecipientUpdate($recipientMock);

        $this->assertEquals(RequestInterface::HTTP_PUT, $recipientUpdate->getMethod());
    }

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $bankAccountMock = $this->getMockBuilder('PagarMe\Sdk\BankAccount\BankAccount')
            ->disableOriginalConstructor()
            ->getMock();

        $bankAccountMock->method('getId')->willReturn(self::BANK_ACCOUNT_ID);
        $bankAccountMock->method('getBankCode')->willReturn(self::BANK_CODE);
        $bankAccountMock->method('getAgencia')->willReturn(self::AGENCIA);
        $bankAccountMock->method('getConta')->willReturn(self::CONTA);
        $bankAccountMock->method('getContaDv')->willReturn(self::CONTA_DV);
        $bankAccountMock->method('getDocumentNumber')->willReturn(self::DOCUMENT_NUMBER);
        $bankAccountMock->method('getLegalName')->willReturn(self::LEGAL_NAME);

        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();

        $recipientMock->method('getBankAccount')->willReturn($bankAccountMock);
        $recipientMock->method('getTransferInterval')
            ->willReturn(self::TRANSFER_INTERVAL);
        $recipientMock->method('getTransferDay')
            ->willReturn(self::TRANSFER_DAY);
        $recipientMock->method('getTransferEnabled')
            ->willReturn(self::TRANSFER_ENABLED);
        $recipientMock->method('getAutomaticAnticipationEnabled')
            ->willReturn(self::AUTOMATIC_ANTICIPATION_ENABLED);
        $recipientMock->method('getAnticipatableVolumePercentage')
            ->willReturn(self::ANTICIPATABLE_VOLUME_PERCENTAGE);

        $recipientUpdate = new RecipientUpdate($recipientMock);

        $this->assertEquals(
            [
                'transfer_interval'               => self::TRANSFER_INTERVAL,
                'transfer_day'                    => self::TRANSFER_DAY,
                'transfer_enabled'                => self::TRANSFER_ENABLED,
                'automatic_anticipation_enabled'  => self::AUTOMATIC_ANTICIPATION_ENABLED,
                'anticipatable_volume_percentage' => self::ANTICIPATABLE_VOLUME_PERCENTAGE,
                'bank_account_id'                 => self::BANK_ACCOUNT_ID,
                'bank_account' => [
                    'bank_code'       => self::BANK_CODE,
                    'agencia'         => self::AGENCIA,
                    'conta'           => self::CONTA,
                    'conta_dv'        => self::CONTA_DV,
                    'document_number' => self::DOCUMENT_NUMBER,
                    'legal_name'      => self::LEGAL_NAME
                ]
            ],
            $recipientUpdate->getPayload()
        );
    }
}
