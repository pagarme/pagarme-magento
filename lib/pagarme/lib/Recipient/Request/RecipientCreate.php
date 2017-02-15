<?php

namespace PagarMe\Sdk\Recipient\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\BankAccount\BankAccount;

class RecipientCreate implements RequestInterface
{

    /**
     * @var BankAccount
     */
    private $bankAccount;

    /**
     * @var string
     */
    private $transferInterval;

    /**
     * @var int
     */
    private $transferDay;

    /**
     * @var null|bool
     */
    private $transferEnabled;

    /**
     * @var null|bool
     */
    private $automaticAnticipationEnabled;

    /**
     * @var int
     */
    private $anticipatableVolumePercentage;


    /**
     * @param BankAccount $bankAccount
     * @param string $transferInterval
     * @param int $transferDay
     * @param null|bool $transferEnabled
     * @param null|bool $automaticAnticipationEnabled
     * @param int $anticipatableVolumePercentage
     */
    public function __construct(
        BankAccount $bankAccount,
        $transferInterval,
        $transferDay,
        $transferEnabled,
        $automaticAnticipationEnabled,
        $anticipatableVolumePercentage
    ) {
        $this->bankAccount                   = $bankAccount;
        $this->transferInterval              = $transferInterval;
        $this->transferDay                   = $transferDay;
        $this->transferEnabled               = $transferEnabled;
        $this->automaticAnticipationEnabled  = $automaticAnticipationEnabled;
        $this->anticipatableVolumePercentage = $anticipatableVolumePercentage;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $payload = [
            'transfer_interval'               => $this->transferInterval,
            'transfer_day'                    => $this->transferDay,
            'transfer_enabled'                => $this->transferEnabled,
            'automatic_anticipation_enabled'  => $this->automaticAnticipationEnabled,
            'anticipatable_volume_percentage' => $this->anticipatableVolumePercentage,
        ];

        return array_merge($payload, $this->getBankAccountData());
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'recipients';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }

    /**
     * @return array
     */
    protected function getBankAccountData()
    {
        $bankAccount = $this->bankAccount;

        if (!is_null($bankAccount->getId())) {
            return ['bank_account_id' => $bankAccount->getId()];
        }

        return [
            'bank_account' =>[
                'bank_code'       => $bankAccount->getBankCode(),
                'agencia'         => $bankAccount->getAgencia(),
                'conta'           => $bankAccount->getConta(),
                'conta_dv'        => $bankAccount->getContaDv(),
                'document_number' => $bankAccount->getDocumentNumber(),
                'legal_name'      => $bankAccount->getLegalName()
            ]
        ];
    }
}
