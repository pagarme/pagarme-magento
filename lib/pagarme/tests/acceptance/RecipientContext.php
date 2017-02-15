<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use PagarMe\Sdk\BankAccount\BankAccount;

class RecipientContext extends BasicContext
{
    private $interval;
    private $day;
    private $transfer;
    private $anticipation;
    private $percentage;

    private $bankAccount;
    private $office;
    private $officeDigit;
    private $accountNumber;
    private $accountDigit;
    private $document;
    private $legalName;

    private $recipient;
    private $recipientId;
    private $recipients;

    /**
     * @Given recipient data :interval, :day, :transfer, :anticipation and :percentage
     */
    public function recipientData($interval, $day, $transfer, $anticipation, $percentage)
    {
        $this->interval     = $this->parseNullParam($interval);
        $this->day          = $this->parseNullParam($day);
        $this->transfer     = $this->parseNullParam($transfer);
        $this->anticipation = $this->parseNullParam($anticipation);
        $this->percentage   = $this->parseNullParam($percentage);
    }

    /**
     * @Given bank data :bankCode, :office, :officeDigit, :accountNumber, :accountDigit, :document and :legalName
     */
    public function bankData(
        $bankCode,
        $office,
        $officeDigit,
        $accountNumber,
        $accountDigit,
        $document,
        $legalName
    ) {
        $this->bankCode      = $this->parseNullParam($bankCode);
        $this->office        = $this->parseNullParam($office);
        $this->officeDigit   = $this->parseNullParam($officeDigit);
        $this->accountNumber = $this->parseNullParam($accountNumber);
        $this->accountDigit  = $this->parseNullParam($accountDigit);
        $this->document      = $this->parseNullParam($document);
        $this->legalName     = $this->parseNullParam($legalName);
    }

    /**
     * @When register the recipient
     */
    public function registerTheRecipient()
    {
        $account = $this->buildAccount(
            $this->bankCode,
            $this->office,
            $this->officeDigit,
            $this->accountNumber,
            $this->accountDigit,
            $this->document,
            $this->legalName
        );

        $this->recipient = self::getPagarMe()
            ->recipient()
            ->create(
                $account,
                $this->interval,
                $this->day,
                $this->transfer,
                $this->anticipation,
                $this->percentage
            );
    }

    /**
     * @When register the recipient with registred account
     */
    public function registerTheRecipientWithRegistredAccount()
    {
        $this->recipient = self::getPagarMe()
            ->recipient()
            ->create(
                $this->bankAccount,
                $this->interval,
                $this->day,
                $this->transfer,
                $this->anticipation,
                $this->percentage
            );
    }

    /**
     * @Then a recipient must be created
     */
    public function aRecipientMustBeCreated()
    {
        assertInstanceOf('PagarMe\Sdk\Recipient\Recipient', $this->recipient);
    }

    /**
     * @When register the account
     */
    public function registerTheAccount()
    {
        $this->bankAccount = self::getPagarMe()
            ->bankAccount()
            ->create(
                $this->bankCode,
                $this->office,
                $this->accountDigit,
                $this->accountDigit,
                $this->document,
                $this->legalName,
                $this->officeDigit
            );
    }


    private function buildAccount(
        $bankCode,
        $office,
        $officeDigit,
        $accountNumber,
        $accountDigit,
        $document,
        $legalName
    ) {
        return new BankAccount(
            [
                'bank_code'       => $this->bankCode,
                'agencia'         => $this->office,
                'agencia_dv'      => $this->officeDigit,
                'conta'           => $this->accountNumber,
                'conta_dv'        => $this->accountDigit,
                'document_number' => $this->document,
                'legal_name'      => $this->legalName
            ]
        );
    }

    private function parseNullParam($param)
    {
        if ($param == 'null') {
            return null;
        }

        return $param;
    }

    /**
     * @Given previous created recipients
     */
    public function previousCreatedRecipients()
    {
        foreach ($this->getRecipientsData() as $recipientData) {
            $this->recipientData(
                $recipientData['interval'],
                $recipientData['day'],
                $recipientData['transfer'],
                $recipientData['anticipation'],
                $recipientData['percentage']
            );

            $this->bankData(
                $recipientData['bank_code'],
                $recipientData['agencia'],
                $recipientData['agencia_dv'],
                $recipientData['conta'],
                $recipientData['conta_dv'],
                $recipientData['document'],
                $recipientData['legal_name']
            );

            $this->registerTheRecipient();
        }
    }

    /**
     * @When query for recipients
     */
    public function queryForRecipients()
    {
        $this->recipients = self::getPagarMe()
            ->recipient()
            ->getList();
    }

    /**
     * @Then a list of recipients must be returned
     */
    public function aListOfRecipientsMustBeReturned()
    {
        assertContainsOnly(
            'PagarMe\Sdk\Recipient\Recipient',
            $this->recipients
        );
    }

    public function getRecipientsData()
    {
        return [
            [
                'interval' => 'null',
                'day' => 'null',
                'transfer' => 'null',
                'anticipation' => 'null',
                'percentage' => 'null',
                'bank_code' => '237',
                'agencia' => '1383',
                'agencia_dv' => '0',
                'conta' => '13399',
                'conta_dv' => '1',
                'document' => '44318031144',
                'legal_name' => 'João'
            ],
            [
                'interval' => 'daily',
                'day' => '0',
                'transfer' => 'true',
                'anticipation' => 'false',
                'percentage' => '50',
                'bank_code' => '341',
                'agencia' => '1384',
                'agencia_dv' => '1',
                'conta' => '13400',
                'conta_dv' => '2',
                'document' => '16360841843',
                'legal_name' => 'Maria'
            ],
            [
                'interval' => 'weekly',
                'day' => '3',
                'transfer' => 'false',
                'anticipation' => 'true',
                'percentage' => 'null',
                'bank_code' => '001',
                'agencia' => '1385',
                'agencia_dv' => '2',
                'conta' => '13401',
                'conta_dv' => '2',
                'document' => '19050151434',
                'legal_name' => 'José'
            ]
        ];
    }

    /**
     * @Given previous created recipient
     */
    public function previousCreatedRecipient()
    {
        $this->recipientData(
            'null',
            'null',
            'null',
            'null',
            'null'
        );

        $this->bankData(
            '237',
            '1383',
            '0',
            '13399',
            '1',
            '44318031144',
            'João'
        );

        $this->registerTheRecipient();
    }

    /**
     * @When query specific
     */
    public function querySpecific()
    {
        $this->recipientId = $this->recipient->getId();

        $this->recipient = self::getPagarMe()
            ->recipient()
            ->get($this->recipientId);
    }

    /**
     * @Then a recipient must be returned
     */
    public function aRecipientMustBeReturned()
    {
        assertEquals($this->recipientId, $this->recipient->getId());
    }

    /**
     * @When I change the recipient transfer interval to weekly
     */
    public function iChangeTheRecipientTransferIntervalToWeekly()
    {
        $this->recipient->setTransferDay(3);
        $this->recipient->setTransferInterval('weekly');
    }

    /**
     * @When make update
     */
    public function makeUpdate()
    {
        $this->recipient = self::getPagarMe()
            ->recipient()
            ->update($this->recipient);
    }

    /**
     * @Then the transfer interval must be weekly
     */
    public function theTransferIntervalMustBeWeekly()
    {
        assertEquals($this->recipient->getTransferDay(), 3);
        assertEquals($this->recipient->getTransferInterval(), 'weekly');
    }

    /**
     * @When I query balance
     */
    public function iQueryBalance()
    {
        $this->balance = self::getPagarMe()
            ->recipient()
            ->balance($this->recipient);
    }

    /**
     * @Then a balance must be returned
     */
    public function aBalanceMustBeReturned()
    {
        assertInstanceOf(
            'PagarMe\Sdk\Balance\Balance',
            $this->balance
        );
    }
}
