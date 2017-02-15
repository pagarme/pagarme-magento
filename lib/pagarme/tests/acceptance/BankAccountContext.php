<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;

class BankAccountContext extends BasicContext
{
    private $bankCode;
    private $office;
    private $accountNumber;
    private $accountDigit;
    private $document;
    private $name;
    private $officeDigit;

    private $bankAccount;
    private $bankAccounts;
    private $queryBankAccount;

    /**
     * @Given following account data :bankCode, :office, :accountNumber, :accountDigit, :document, :name and :officeDigit
     */
    public function followingAccountData(
        $bankCode,
        $office,
        $accountNumber,
        $accountDigit,
        $document,
        $name,
        $officeDigit
    ) {
        $this->bankCode      = $bankCode;
        $this->office        = $office;
        $this->accountNumber = $accountNumber;
        $this->accountDigit  = $accountDigit;
        $this->document      = $document;
        $this->name          = $name;
        $this->officeDigit   = $officeDigit;

        if ($officeDigit == 'null') {
            $this->officeDigit = null;
        }
    }

    /**
     * @When register the bank account
     */
    public function registerTheBankAccount()
    {
        $this->bankAccount = self::getPagarMe()
            ->bankAccount()
            ->create(
                $this->bankCode,
                $this->office,
                $this->accountNumber,
                $this->accountDigit,
                $this->document,
                $this->name,
                $this->officeDigit
            );
    }

    /**
     * @Then a account must be created
     */
    public function aAccountMustBeCreated()
    {
        assertInstanceOf(
            'PagarMe\Sdk\BankAccount\BankAccount',
            $this->bankAccount
        );
    }

    /**
     * @Then must account contain same data
     */
    public function mustAccountContainSameData()
    {
        assertEquals($this->bankCode, $this->bankAccount->getBankCode());
        assertEquals($this->office, $this->bankAccount->getAgencia());
        assertEquals($this->accountNumber, $this->bankAccount->getConta());
        assertEquals($this->accountDigit, $this->bankAccount->getContaDv());
        assertEquals($this->document, $this->bankAccount->getDocumentNumber());
        assertEquals($this->name, $this->bankAccount->getLegalName());
        assertEquals($this->officeDigit, $this->bankAccount->getAgenciaDv());
    }

    /**
     * @Given a previous created bank accounts
     */
    public function aPreviousCreatedBankAccounts()
    {
        $samples = [
            [
                'bankCode'      => 001,
                'office'        => 1977,
                'accountNumber' => 1935,
                'accountDigit'  => 1,
                'document'      => 67178880244,
                'name'          => 'Maria Silva',
                'officeDigit'   => 1
            ],
            [
                'bankCode'      => 033,
                'office'        => 1986,
                'accountNumber' => 01020,
                'accountDigit'  => 2,
                'document'      => 75232346660,
                'name'          => 'Carlos Silva',
                'officeDigit'   => null
            ],
            [
                'bankCode'      => 104,
                'office'        => 1991,
                'accountNumber' => 10001,
                'accountDigit'  => 3,
                'document'      => 13067245890,
                'name'          => 'Cesar Silva',
                'officeDigit'   => 3
            ],
            [
                'bankCode'      => 237,
                'office'        => 2006,
                'accountNumber' => 80486,
                'accountDigit'  => 4,
                'document'      => 26260865686,
                'name'          => 'Luiza Silva',
                'officeDigit'   => null
            ],
            [
                'bankCode'      => 341,
                'office'        => 2007,
                'accountNumber' => 23350,
                'accountDigit'  => 5,
                'document'      => 11663782687,
                'name'          => 'Joao Silva',
                'officeDigit'   => null
            ]
        ];

        foreach ($samples as $sample) {
            self::getPagarMe()
                ->bankAccount()
                ->create(
                    $sample['bankCode'],
                    $sample['office'],
                    $sample['accountNumber'],
                    $sample['accountDigit'],
                    $sample['document'],
                    $sample['name'],
                    $sample['officeDigit']
                );
        }
        sleep(1);
    }

    /**
     * @When I query for bank accounts
     */
    public function iQueryForBankAccounts()
    {
        $this->bankAccounts = self::getPagarMe()
                ->bankAccount()
                ->getList();
    }

    /**
     * @Then a list of bank account must be returned
     */
    public function aListOfBankAccountMustBeReturned()
    {
        assertGreaterThanOrEqual(5, count($this->bankAccounts));
        assertContainsOnly(
            'PagarMe\Sdk\BankAccount\BankAccount',
            $this->bankAccounts
        );
    }

     /**
     * @Given a previous created bank account
     */
    public function aPreviousCreatedBankAccount()
    {
        $this->bankAccount = self::getPagarMe()
                ->bankAccount()
                ->create(
                    104,
                    1991,
                    10001,
                    3,
                    13067245890,
                    'Cesar Silva',
                    3
                );
    }

    /**
     * @When I query for created bank account
     */
    public function iQueryForCreatedBankAccount()
    {
        $this->queryBankAccount = self::getPagarMe()
                ->bankAccount()
                ->get(
                    $this->bankAccount->getId()
                );
    }

    /**
     * @Then a bank account must be returned
     */
    public function aBankAccountMustBeReturned()
    {
        assertInstanceOf(
            'PagarMe\Sdk\BankAccount\BankAccount',
            $this->bankAccount
        );
    }

     /**
     * @Then must the same bank account
     */
    public function mustTheSameBankAccount()
    {
        assertEquals($this->bankAccount, $this->queryBankAccount);
    }
}
