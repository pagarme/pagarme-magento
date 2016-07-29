<?php

abstract class PagarMeTestCase extends UnitTestCase {


	protected static function setAntiFraud($status) {
		// authorizeFromEnv();
		// $request = new PagarMe_Request('/company', 'PUT');
		// $request->setParameters(array('antifraud' => $status));
		// $response = $request->run();
	}

	protected static function createTestTransaction(array $attributes = array()) {
		authorizeFromEnv();
		return new PagarMe_Transaction(
			$attributes +
			array(
			"amount" => '1000',
			"card_number" => "4901720080344448",
			"card_holder_name" => "Jose da Silva",
			"card_expiration_month" => '12',
			"card_expiration_year" => '22',
			"card_cvv" => "123",
		));
	}

	protected static function createTestCustomer(array $attributes = array()) {
		$customer = array(
			'name' => "Jose da Silva",
			'document_number' => "36433809847",
			'email' => "customer@pagar.me",
			'address' => array(
				'street' => "Av Faria Lima",
				'neighborhood' => 'Jardim Europa',
				'zipcode' => '01452000',
				'street_number' => '296',
				'complementary' => '8 andar'
			),
			'phone' => array(
				'ddd' => '12',
				'number' => '999999999',
			),
			'sex' => 'M',
			'born_at' => '1995-10-11');
		return $customer;
	}

	protected static function createTestCard(array $attributes = array()) {
		authorizeFromEnv();
		return new PagarMe_Card(array(
			'card_number' => '4111111111111111',
			'card_holder_name' => 'Jose da Silva',
			'card_expiration_month' => '10',
			'card_expiration_year' => '22',
			'card_cvv' => '123',
		));
	}

	protected static function createTestTransactionWithCustomer(array $attributes = array()) {
		authorizeFromEnv();
		$transaction = self::createTestTransaction();
		$transaction->customer = self::createTestCustomer();
		return $transaction;
	}

	protected static function createTestPlan(array $attributes = array()) {
		authorizeFromEnv();
		return new PagarMe_Plan($attributes +
			array(
				'amount' => 1000,
				'days' => '30',
				'name' => "Plano Silver",
				'trial_days' => '2'
			)
		);
	}

	protected function validatePlan($plan) {
		$this->assertTrue($plan->getId());
		$this->assertEqual($plan->getAmount(), '1000');
		$this->assertEqual($plan->getName(), 'Plano Silver');
		$this->assertEqual($plan->getTrialDays(), '2');
	}


	protected static function createTestSubscription(array $attributes = array()) {
		authorizeFromEnv();
		return new PagarMe_Subscription($attributes + array(
			"card_number" => "4901720080344448",
			"card_holder_name" => "Jose da Silva",
			"card_expiration_month" => 12,
			"card_expiration_year" => 22,
			"card_cvv" => "123",
			'customer' => array(
				'email' => 'customer@pagar.me'
			)
		));
	}

	protected static function createTestBankAccount(array $attributes = array()) {
		authorizeFromEnv();

		return new PagarMe_Bank_Account(array(
			"bank_code" => "341",
			"agencia" => "0932",
			"agencia_dv" => "5",
			"conta" => "58054",
			"conta_dv" => "1",
			"document_number" => "26268738888",
			"legal_name" => "API BANK ACCOUNT"
		));
	}

	protected static function createTestRecipient(array $attributes = array()) {
		authorizeFromEnv();

		return new PagarMe_Recipient(array(
			"transfer_interval" => "weekly",
			"transfer_day" => 5,
			"transfer_enabled" => true,
			"automatic_anticipation_enabled" => true,
			"anticipatable_volume_percentage" => 85,
			"bank_account" => array(
				"bank_code" => "341",
				"agencia" => "0932",
				"agencia_dv" => "5",
				"conta" => "58054",
				"conta_dv" => "1",
				"document_number" => "26268738888",
				"legal_name" => "API BANK ACCOUNT",
			)
		));
	}	

	protected static function createSubscriptionWithCustomer(array $attributes = array()) {
		authorizeFromEnv();
		$subscription = self::createTestSubscription();
		$subscription->customer = self::createTestCustomer();
		return $subscription;
	}

	protected function validateCustomerResponse($customer) {
		authorizeFromEnv();
		$this->assertTrue($customer->getId());
		$this->assertEqual($customer->getDocumentType(), 'cpf');
		$this->assertEqual($customer->getName(), 'Jose da Silva');
		$this->assertTrue($customer->getBornAt());
		$this->assertEqual($customer->getGender(), 'M');

	}

	protected static function createTestSet() {
		return new PagarMe_Set(array('key', 'value', 'key', 'value', 'abc', 'bcd', 'kkkk'));
	}

	protected static function createPagarMeObject() {
		$response = array("status"=> "paid",
			"object" => 'transaction',
			"refuse_reason" => null,
			"date_created" => "2013-09-26T03:19:36.000Z",
			"amount" => 1590,
			"installments" => 1,
			"id" => 1379,
			"card_holder_name" => "Jose da Silva",
			"card_last_digits" => "4448",
			"card_brand" => "visa",
			"postback_url" => null,
			"payment_method" => "credit_card",
			"customer" => array(
				'object' => 'customer',
				"document_number" => "51472745531",
				'address' => array(
					'object' => "address",
					'street' => 'asdas'
				)
			));
		return PagarMe_Object::build($response, 'PagarMe_Transaction');
	}

	protected function validateAddress($addr) {
		$this->assertEqual($addr->getStreet(), 'Av Faria Lima');
		$this->assertEqual($addr->getNeighborhood(), 'Jardim Europa');
		$this->assertEqual($addr->getZipcode(), '01452000');
		$this->assertEqual($addr->getComplementary(), '8 andar');
		$this->assertEqual($addr->getStreetNumber(), '296');
	}

	protected function validatePhone($phone) {
		$this->assertEqual($phone->getDdd(), '12');
		$this->assertEqual($phone->getNumber(), '999999999');
	}

	protected function validateSubscription($subscription) {
		if($subscription->getCustomer()->getName()) {
			$this->validateCustomerResponse($subscription->getCustomer());
		}

		if($subscription->getPlan()) {
			$this->validatePlan($subscription->getPlan());
		}

		$this->assertTrue($subscription->getId());
		$this->assertEqual($subscription->getCustomer()->getEmail(), 'customer@pagar.me');
	}

	protected function validateTransactionResponse($transaction) {
		authorizeFromEnv();

		$this->assertTrue($transaction->getId());

		if ($transaction->getPaymentMethod() == 'credit_card') {
			$this->assertEqual($transaction->getCardHolderName(), 'Jose da Silva');
		}

		$this->assertTrue($transaction->getDateCreated());
		$this->assertEqual($transaction->getAmount(), 1000);
		$this->assertEqual($transaction->getInstallments(), '1');
		// $this->assertEqual($transaction->getStatus(), 'paid');
		$this->assertFalse($transaction->getRefuseReason());

		if($transaction->getCustomer()) {
			$customer = $transaction->getCustomer();
			$this->validateCustomerResponse($customer);
		}

		if($transaction->getAddress()) {
			$this->validateAddress($transaction->getAddress());
		}

		if($transaction->getPhone()) {
			$this->validatePhone($transaction->getPhone());
		}
	}

}
