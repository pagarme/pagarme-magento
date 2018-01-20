<?php
/**
 * @author LeonamDias <leonam.pd@gmail.com>
 * @package PHP
 */

use PagarMe\Sdk\Card\Card;
use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe_CreditCard_Model_Creditcard as ModelCreditCard;

class PagarMeCreditCardModelCreditcardTest extends PHPUnit_Framework_TestCase
{
    private $creditCardModel;

    public function setUp()
    {
        $this->creditCardModel = $this->getMockBuilder(
            'PagarMe_CreditCard_Model_Creditcard'
        )->setMethods(['getMaxInstallments'])
        ->getMock();

        $this->creditCardModel
            ->method('getMaxInstallments')
            ->willReturn(6);
    }

    /**
     * @test
     */
    public function mustBeAnInstanceOfPagarmeCreditCardModel()
    {
        $this->assertInstanceOf(
            'PagarMe_CreditCard_Model_Creditcard',
            $this->creditCardModel
        );
    }

    /**
     * Returns installments with expected return from isInstallmentsValid
     * method
     *
     * @return array
     */
    public function invalidInstallments()
    {
        $installmentBellowRange = 0;
        $installmentAboveRange = 7;
        $installmentAbovePagarmeRange = 13;

        return [
            [$installmentBellowRange],
            [$installmentAboveRange],
            [$installmentAbovePagarmeRange]
        ];
    }
    /**
     * @param int $installments
     *
     * @test
     * @dataProvider invalidInstallments
     * @expectedException PagarMe_CreditCard_Model_Exception_InvalidInstallments
     */
    public function installmentsMustBeInAValidRange($installments)
    {
        $this->creditCardModel->isInstallmentsValid($installments);
    }

    public function getSdkMock($cardHash = '')
    {
        $sdkMock = $this->getMockBuilder('\PagarMe\Sdk\PagarMe')
            ->setMethods([
                'card',
                'createFromHash',
                'transaction',
                'creditCardTransaction',
                'capture'
            ])
            ->getMock();

        $sdkMock->expects($this->any())
            ->method('card')
            ->willReturnSelf();

        $sdkMock->expects($this->any())
            ->method('createFromHash')
            ->with($cardHash)
            ->willReturn(new Card([]));

        return $sdkMock;
    }

    /**
     * @test
     */
    public function mustReturnACardInstance()
    {
        $cardHash = 'test_transaction_e8Ij0oYalvjTEO17IHqKxNQcigKrYj';
        $sdk = $this->getSdkMock($cardHash);

        $creditCardModel = Mage::getModel('pagarme_creditcard/creditcard');
        $creditCardModel->setSdk($sdk);

        $card = $creditCardModel->generateCard($cardHash);

        $this->assertInstanceOf('\PagarMe\Sdk\Card\Card', $card);
    }

    /**
     * @test
     *
     * @expectedException PagarMe_CreditCard_Model_Exception_GenerateCard
     */
    public function mustThrowAGenerateCardExceptionInCaseOfErrors()
    {
        $cardHash = '';
        $sdk = $this->getSdkMock($cardHash);

        $sdk->expects($this->any())
            ->method('createFromHash')
            ->with($cardHash)
            ->will(
                $this->onConsecutiveCalls(
                    $this->throwException(new \PagarMe\Sdk\ClientException()),
                    $this->throwException(new \Exception())
                )
            );

        $creditCardModel = Mage::getModel('pagarme_creditcard/creditcard');
        $creditCardModel->setSdk($sdk);

        $creditCardModel->generateCard($cardHash);
        $creditCardModel->generateCard($cardHash);
    }

    /**
     * @test
     * @expectedException PagarMe_CreditCard_Model_Exception_TransactionsInstallmentsDivergent
     */
    public function mustThrowAnExceptionIfInstallmentsIsDifferent()
    {
        $sdk = $this->getSdkMock();

        $transaction = new CreditCardTransaction(['installments' => 2]);

        $sdk->expects($this->any())
            ->method('transaction')
            ->willReturnSelf();

        $sdk->expects($this->any())
            ->method('creditCardTransaction')
            ->willReturn($transaction);

        $creditCardModel = Mage::getModel('pagarme_creditcard/creditcard');
        $creditCardModel->setSdk($sdk);

        $card = $this->getMockBuilder('PagarMe\Sdk\Card\Card')
            ->disableOriginalConstructor()
            ->getMock();

        $customer = $this->getMockBuilder(
            'PagarMe\Sdk\Customer\Customer'
            )
            ->disableOriginalConstructor()
            ->getMock();

        $expectedInstallments = 3;
        $creditCardModel->createTransaction(
            $card,
            $customer,
            $expectedInstallments,
            false
        );
        $creditCardModel->checkInstallments();
    }

    /**
     * @test
     */
    public function authorizedTransactionShouldBePaidAfterCapture()
    {
        $sdk = $this->getSdkMock();
        $sdk->expects($this->any())
            ->method('transaction')
            ->willReturnSelf();

        $authTransaction = new CreditCardTransaction(
            ['status' => ModelCreditCard::AUTHORIZED_TRANSACTION]
        );

        $sdk->expects($this->any())
            ->method('creditCardTransaction')
            ->willReturn($authTransaction);

        $paidTransaction = new CreditCardTransaction(
            ['status' => ModelCreditCard::PAID_TRANSACTION]
        );
        $sdk->expects($this->any())
            ->method('capture')
            ->willReturn($paidTransaction);

        $creditCardModel = Mage::getModel('pagarme_creditcard/creditcard');
        $creditCardModel->setSdk($sdk);

        $card = $this->getMockBuilder('PagarMe\Sdk\Card\Card')
            ->disableOriginalConstructor()
            ->getMock();

        $customer = $this->getMockBuilder(
            'PagarMe\Sdk\Customer\Customer'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $installments = 1;
        $capture = false;

        $creditCardModel->createTransaction(
            $card,
            $customer,
            $installments,
            $capture
        );

        $this->assertFalse($creditCardModel->transactionIsPaid());
        $creditCardModel->capture();
        $this->assertTrue($creditCardModel->transactionIsPaid());
    }
}
