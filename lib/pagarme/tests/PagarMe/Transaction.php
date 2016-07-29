<?php

class PagarMe_TransactionTest extends PagarMeTestCase {

	public function testCharge() {
		$transaction = self::createTestTransaction();
		$this->assertFalse($transaction->getId());
		$transaction->charge();
		$this->validateTransactionResponse($transaction);
	}

	public function testAntifraudTransaction() {
		$t = self::createTestTransactionWithCustomer();
		$t->charge();
		$this->validateTransactionResponse($t);
	}

	public function testPostbackUrl() {
		$t = self::createTestTransaction();
		$t->setPostbackUrl('http://url.com');
		$t->charge();

		$this->assertEqual($t->getStatus(), 'processing');
	}

	public function testCalculateInstallmentsAmount() {
		$request = PagarMe_Transaction::calculateInstallmentsAmount('10000', '1.5', '12');
		$installments = $request['installments'];
		$this->assertEqual($installments["5"]["amount"], 10750);
		$this->assertEqual($installments["5"]["installment"],  '5');
		$this->assertEqual($installments["5"]["installment_amount"],  2150);
	}

	public function testCalculateInstallmentsAmountWithFreeInstallmentsWithoutInterest()
	{
		$request = PagarMe_Transaction::calculateInstallmentsAmount('90000', '2', '3', 3);
		$installments = $request['installments'];
		$this->assertEqual($installments["3"]["amount"], 90000);
		$this->assertEqual($installments["3"]["installment"],  '3');
		$this->assertEqual($installments["3"]["installment_amount"],  30000);
	}

	public function testCalculateInstallmentsAmountWithZeroFreeInstallments()
	{
		$request = PagarMe_Transaction::calculateInstallmentsAmount('90000', '2', '3', 0);
		$installments = $request['installments'];
		$this->assertEqual($installments["1"]["amount"], 90000);
		$this->assertEqual($installments["1"]["installment"], '1');
		$this->assertEqual($installments["1"]["installment_amount"],  90000);
	}

	public function testCalculateInstallmentsAmountWithThreeFreeInstallments()
	{
		$request = PagarMe_Transaction::calculateInstallmentsAmount('90000', '2', '9', 3);
		$installments = $request['installments'];
		$this->assertEqual($installments["3"]["amount"], 90000);
		$this->assertEqual($installments["3"]["installment"],  '3');
		$this->assertEqual($installments["3"]["installment_amount"],  30000);
	}

	public function testCalculateInstallmentsAmountWithInvalidFreeInstallments()
	{
		$request = PagarMe_Transaction::calculateInstallmentsAmount('90000', '2', '9', 13);
		$installments = $request['installments'];
		$this->assertEqual($installments["3"]["amount"], 90000);
		$this->assertEqual($installments["3"]["installment"],  '3');
		$this->assertEqual($installments["3"]["installment_amount"],  30000);
	}

	public function testPostbackWithBoleto() {
		$t = self::createTestTransactionWithCustomer();
		$t->setPaymentMethod('boleto');
		$t->setPostbackUrl('http://requestb.in/1a4cif91');
		$t->charge();

		$this->assertEqual($t->getStatus(), 'waiting_payment');
	}

	public function testSeparateAuthAndCapture() {
		$t = self::createTestTransaction();
		$t->setCapture(false);
		$t->charge();

		$this->assertEqual($t->getStatus(), 'authorized');

		$t->capture();

		$this->assertEqual($t->getStatus(), 'paid');
	}

	public function testPartialCapture() {
		$t = self::createTestTransaction();
		$t->setCapture(false);
		$t->charge();

		$this->assertEqual($t->getStatus(), 'authorized');

		$t->capture(1000);
		$this->assertEqual($t->getAmount(), 1000);
		$this->assertEqual($t->getStatus(), 'paid');
	}

	public function testPostbackUrlWithCardHash() {
		$t = self::createTestTransactionWithCustomer();
		$card_hash = $t->generateCardHash();

		$t->setPostbackUrl('http://url.com');
		$t->charge();

		$this->validateTransactionResponse($t);

		$this->assertEqual($t->getPostbackUrl(), 'http://url.com');
		$this->assertEqual($t->getStatus(), 'processing');
	}

	public function testChargeWithCardHash() {
		$t = self::createTestTransactionWithCustomer();
		$card_hash = $t->generateCardHash();

		$transaction = self::createTestTransactionWithCustomer();
		$transaction->setCardHash($card_hash);
		$transaction->charge();
		$this->validateTransactionResponse($transaction);
	}

	public function testTransactionWithBoleto() {
		authorizeFromEnv();
		$t1 = self::createTestTransaction();
		$t1->setPaymentMethod('boleto');
		$t1->charge();

		$this->validateTransactionResponse($t1);

		$t2 = self::createTestTransactionWithCustomer();
		$t2->setPaymentMethod('boleto');
		$t2->charge();

		$this->validateTransactionResponse($t2);


		$this->assertEqual($t2->getPaymentMethod(), 'boleto');
		$this->assertEqual($t2->getBoletoUrl(), 'https://pagar.me');
		$this->assertTrue($t2->getBoletoBarcode());
	}

	public function testTransactionWithUnsavedCardObject() {
		$card = self::createTestCard();
		$transaction = new PagarMe_Transaction(array(
			'amount' => 10000,
			'payment_method' => 'credit_card',
		));

		$transaction->setCard($card);
		$transaction->charge();

		$this->assertEqual($transaction->getStatus(), 'paid');
	}

	public function testTransactionWithSavedCardObject() {
		$card = self::createTestCard();
		$card->create();

		$transaction = new PagarMe_Transaction(array(
			'amount' => 10000,
			'payment_method' => 'credit_card',
		));

		$transaction->setCard($card);
		$transaction->charge();

		$this->assertEqual($transaction->getStatus(), 'paid');
	}

	public function testTransactionWithReturnedCard() {
		$transaction = self::createTestTransaction();
		$transaction->charge();

		$card = $transaction->getCard();

		$transaction2 = new PagarMe_Transaction(array(
			'amount' => 123456,
			'payment_method' => 'credit_card',
		));
		$transaction2->setCard($card);
		$transaction2->charge();

		$this->assertEqual($transaction->getStatus(), 'paid');
	}

	public function testPostback() {
		$transaction = self::createTestTransaction();
		$transaction->setPostbackUrl('abc2');

		$this->assertEqual('abc2', $transaction->getPostbackUrl());
	}

	public function testRefund() {
		$transaction = self::createTestTransaction();
		$transaction->charge();
		$this->validateTransactionResponse($transaction);
		$transaction->refund();
		$this->assertEqual($transaction->getStatus(), 'refunded');

		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->refund();
	}

	public function testBoletoRefund() {
		$transaction = self::createTestTransaction();
		$transaction->setPaymentMethod('boleto');
		$transaction->charge();

		$transaction->setStatus('paid');
		$transaction->save();

		$transaction->refund(array(
			'bank_account' => array(
				'bank_code' => '001',
				'agencia' => '1111',
				'agencia_dv' => '1',
				'conta' => '11111111',
				'conta_dv' => '1',
				'document_number' => '11111111111',
				'legal_name' => 'Jose da Silva',
			),
		));

		$this->assertEqual('pending_refund', $transaction->getStatus());
	}

	public function testCreation() {
		$transaction = self::createTestTransaction();
		$this->assertEqual($transaction->getStatus(), 'local');
		$this->assertEqual($transaction->getPaymentMethod(), 'credit_card');
	}

	public function testMetadata() {
		$transaction = self::createTestTransaction();
		$transaction->setMetadata(array('event' => array('name' => "Evento irado", 'quando'=> 'amanha')));
		$transaction->charge();
		$this->assertTrue($transaction->getId());

		$transaction2 = PagarMe_Transaction::findById($transaction->getId());
		$metadata = $transaction2->getMetadata();
		$this->assertEqual($metadata['event']['name'], "Evento irado");
	}

	public function testDeepMetadata() {
		$transaction = self::createTestTransaction();
		$transaction->setMetadata(array('basket' => array('session' => array('date' => "31/04/2014", 'time' => "12:00:00"), 'ticketTypeId'=> '5209', 'type' => "inteira", 'quantity' => '1', 'price' => 2000)));
		$transaction->charge();
		$this->assertTrue($transaction->getId());

		$transaction2 = PagarMe_Transaction::findById($transaction->getId());
		$metadata = $transaction2->getMetadata();
		$this->assertEqual($metadata['basket']['quantity'], "1");
		$this->assertEqual($metadata['basket']['session']['date'], "31/04/2014");
	}

	public function testValidation() {
		$transaction = new PagarMe_Transaction();
		$transaction->setCardNumber("123");
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setCardNumber('4111111111111111');

		$transaction->setCardHolderName('');
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setCardHolderName("Jose da silva");

		$transaction->setExpiracyMonth(13);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setExpiracyMonth(12);

		$transaction->setExpiracyYear(10);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setExpiracyYear(16);

		$transaction->setCvv(123456);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setCvv(123);

		$transaction->setAmount(0);
		$this->expectException(new IsAExpectation('PagarMe_Exception'));
		$transaction->charge();
		$transaction->setAmount(1000);
	}

	public function testFingerprint() {
		$expectedResult    = "sha1=7820fcb6d03ec8f721c14596654d24623af9e7de";
		$this->assertTrue(PagarMe::validateRequestSignature('{"sample":"payload","value":true}', $expectedResult));

		$expectedResult = "sha1=hash_errado";
		$this->assertFalse(PagarMe::validateRequestSignature('{"sample":"payload","value":true}', $expectedResult));
	}
}
