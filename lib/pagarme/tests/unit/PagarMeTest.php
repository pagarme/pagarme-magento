<?php


namespace PagarMe\SdkTest;

use PagarMe\Sdk\PagarMe;

class PagarMeTest extends \PHPUnit_Framework_TestCase
{
    private $pagarMe;

    public function setup()
    {
        $this->pagarMe = new PagarMe('apiKey');
    }

    /**
     * @test
     */
    public function mustReturnSameCustomerHandler()
    {

        $customerHandlerA = $this->pagarMe->customer();
        $customerHandlerB = $this->pagarMe->customer();

        $this->assertSame($customerHandlerA, $customerHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Customer\CustomerHandler',
            $customerHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameTransactionHandler()
    {
        $transactionHandlerA = $this->pagarMe->transaction();
        $transactionHandlerB = $this->pagarMe->transaction();
        $this->assertSame($transactionHandlerA, $transactionHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Transaction\TransactionHandler',
            $transactionHandlerA
        );
    }


    /**
     * @test
     */
    public function mustReturnSameCardHandler()
    {
        $cardHandlerA = $this->pagarMe->card();
        $cardHandlerB = $this->pagarMe->card();

        $this->assertSame($cardHandlerA, $cardHandlerB);
        $this->assertInstanceOf('PagarMe\Sdk\Card\CardHandler', $cardHandlerA);
    }

    /**
     * @test
     */
    public function mustReturnSameCalculationHandler()
    {
        $calculationHandlerA = $this->pagarMe->calculation();
        $calculationHandlerB = $this->pagarMe->calculation();

        $this->assertSame($calculationHandlerA, $calculationHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Calculation\CalculationHandler',
            $calculationHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnRecipientsHandler()
    {
        $recipientHandlerA = $this->pagarMe->recipient();
        $recipientHandlerB = $this->pagarMe->recipient();

        $this->assertInstanceOf(
            'PagarMe\Sdk\Recipient\RecipientHandler',
            $recipientHandlerA
        );

        $this->assertSame($recipientHandlerA, $recipientHandlerB);
    }

    /**
     * @test
     */
    public function mustReturnSamePlanHandler()
    {

        $planHandlerA = $this->pagarMe->plan();
        $planHandlerB = $this->pagarMe->plan();

        $this->assertSame($planHandlerA, $planHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Plan\PlanHandler',
            $planHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameSplitRuleHandler()
    {
        $splitRuleHandlerA = $this->pagarMe->splitRule();
        $splitRuleHandlerB = $this->pagarMe->splitRule();

        $this->assertSame($splitRuleHandlerA, $splitRuleHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\SplitRule\SplitRuleHandler',
            $splitRuleHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameTransferHandler()
    {

        $transferHandlerA = $this->pagarMe->transfer();
        $transferHandlerB = $this->pagarMe->transfer();

        $this->assertSame($transferHandlerA, $transferHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Transfer\TransferHandler',
            $transferHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameCompanyHandler()
    {

        $companyHandlerA = $this->pagarMe->company();
        $companyHandlerB = $this->pagarMe->company();

        $this->assertSame($companyHandlerA, $companyHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Company\CompanyHandler',
            $companyHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameBankAccountHandler()
    {

        $bankAccountHandlerA = $this->pagarMe->bankAccount();
        $bankAccountHandlerB = $this->pagarMe->bankAccount();

        $this->assertSame($bankAccountHandlerA, $bankAccountHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\BankAccount\BankAccountHandler',
            $bankAccountHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameSubscriptionHandler()
    {

        $subscriptionHandlerA = $this->pagarMe->subscription();
        $subscriptionHandlerB = $this->pagarMe->subscription();

        $this->assertSame($subscriptionHandlerA, $subscriptionHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Subscription\SubscriptionHandler',
            $subscriptionHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameBulkAnticipationHandler()
    {

        $bulkAnticipationHandlerA = $this->pagarMe->bulkAnticipation();
        $bulkAnticipationHandlerB = $this->pagarMe->bulkAnticipation();

        $this->assertSame($bulkAnticipationHandlerA, $bulkAnticipationHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\BulkAnticipation\BulkAnticipationHandler',
            $bulkAnticipationHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSamePayableHandler()
    {

        $payableHandlerA = $this->pagarMe->payable();
        $payableHandlerB = $this->pagarMe->payable();

        $this->assertSame($payableHandlerA, $payableHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Payable\PayableHandler',
            $payableHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameZipcodeHandler()
    {

        $zipcodeHandlerA = $this->pagarMe->zipcode();
        $zipcodeHandlerB = $this->pagarMe->zipcode();

        $this->assertSame($zipcodeHandlerA, $zipcodeHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Zipcode\ZipcodeHandler',
            $zipcodeHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSamePostbackHandler()
    {

        $postbackHandlerA = $this->pagarMe->postback();
        $postbackHandlerB = $this->pagarMe->postback();

        $this->assertSame($postbackHandlerA, $postbackHandlerB);
        $this->assertInstanceOf(
            'PagarMe\Sdk\Postback\PostbackHandler',
            $postbackHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameAntifraudAnalysisHandler()
    {

        $antifraudAnalysisHandlerA = $this->pagarMe->antifraudAnalysis();
        $antifraudAnalysisHandlerB = $this->pagarMe->antifraudAnalysis();

        $this->assertSame(
            $antifraudAnalysisHandlerA,
            $antifraudAnalysisHandlerB
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\AntifraudAnalysis\AntifraudAnalysisHandler',
            $antifraudAnalysisHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameSearchHandler()
    {

        $searchHandlerA = $this->pagarMe->search();
        $searchHandlerB = $this->pagarMe->search();

        $this->assertSame($searchHandlerA, $searchHandlerB);

        $this->assertInstanceOf(
            'PagarMe\Sdk\Search\SearchHandler',
            $searchHandlerA
        );
    }

    /**
     * @test
     */
    public function mustReturnSameBalanceHandler()
    {
        $balanceHandlerA = $this->pagarMe->balance();
        $balanceHandlerB = $this->pagarMe->balance();

        $this->assertSame($balanceHandlerA, $balanceHandlerB);

        $this->assertInstanceOf(
            'PagarMe\Sdk\Balance\BalanceHandler',
            $balanceHandlerA
        );
    }
}
