<?php

namespace PagarMe\Sdk\BankAccount\Request;

use PagarMe\Sdk\RequestInterface;

class BankAccountCreate implements RequestInterface
{
    /**
     * @var int
     */
    private $bankCode;

    /**
     * @var int
     */
    private $office;

    /**
     * @var int
     */
    private $officeDigit;

    /**
     * @var int
     */
    private $accountNumber;

    /**
     * @var int
     */
    private $accountDigit;

    /**
     * @var int
     */
    private $documentNumber;

    /**
     * @var string
     */
    private $legalName;

    /**
     * @param int $bankCode
     * @param int $office
     * @param int $accountNumber
     * @param int $accountDigit
     * @param int $documentNumber
     * @param string $legalName
     * @param int $officeDigit
     */
    public function __construct(
        $bankCode,
        $office,
        $accountNumber,
        $accountDigit,
        $documentNumber,
        $legalName,
        $officeDigit
    ) {
        $this->bankCode       = $bankCode;
        $this->office         = $office;
        $this->accountNumber  = $accountNumber;
        $this->accountDigit   = $accountDigit;
        $this->documentNumber = $documentNumber;
        $this->legalName      = $legalName;
        $this->officeDigit    = $officeDigit;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'bank_code'       => $this->bankCode,
            'agencia'         => $this->office,
            'conta'           => $this->accountNumber,
            'conta_dv'        => $this->accountDigit,
            'document_number' => $this->documentNumber,
            'legal_name'      => $this->legalName,
            'agencia_dv'      => $this->officeDigit
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'bank_accounts';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
