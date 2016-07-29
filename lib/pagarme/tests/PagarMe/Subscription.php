<?php

class PagarMe_SubscriptionTest extends PagarMeTestCase {

	public function testCreate() {
		$subscription = self::createTestSubscription();
		$subscription->create();
		$this->validateSubscription($subscription);
	}

	public function testCreateAndSave() {
		$subscription = self::createTestSubscription();
		$subscription->create();
		$subscription->save();
		$this->validateSubscription($subscription);
	}

	public function testSubscriptionTransactions() {
		$subscription = self::createTestSubscription();
		$subscription->create();
		$subscription->charge(1000);
		$subscription->charge(2000);

		$transactions = $subscription->getTransactions();

		$this->assertEqual(sizeof($transactions), 2);
		$this->assertEqual($transactions[1]->amount, 1000);
		$this->assertEqual($transactions[0]->amount, 2000);
	}

	public function testUpdate() {
		$subscription = self::createTestSubscription();
		$subscription->create();

		$subscription->setPaymentMethod('boleto');
		$subscription->save();

		$subscription2 = PagarMe_Subscription::findById($subscription->getId());
		$this->assertEqual($subscription2->getPaymentMethod(), 'boleto');
	}

	public function testUpdatePlan() {
		$subscription = self::createTestSubscription();
		$plan = self::createTestPlan();
		$plan->create();
		$subscription->setPlan($plan);
		$subscription->create();

		$plan2 = new PagarMe_Plan(array(
			'name' => 'Plano 2',
			'days' => '10',
			'amount' => 4500,
			'payment_method' => "credit_card",
			'trial_days' => "3"
		));
		$plan2->create();

		$subscription->plan = $plan2;
		$subscription->save();

		$s2 = PagarMe_Subscription::findById($subscription->id);

		$this->assertEqual($s2->plan->id, $plan2->id);
	}

	public function testCreateWithFraud() {
		$subscription = self::createSubscriptionWithCustomer();
		$subscription->create();
		$this->validateSubscription($subscription);
	}

	public function testCreateWithPlanAndFraud() {
		$subscription = self::createSubscriptionWithCustomer();
		$plan = self::createTestPlan();
		$plan->create();
		$subscription->setPlan($plan);

		$subscription->create();
		$this->validateSubscription($subscription);
		$this->assertTrue($subscription->getId());
		$this->assertTrue($subscription->getCustomer());
		$this->assertTrue($subscription->getPlan()->getId());
		$this->assertTrue($plan->getId());

		$subscription2 = PagarMe_Subscription::findById($subscription->getId());
		$this->assertTrue($subscription2->getPlan());
		$this->assertEqual($subscription2->getPlan()->getId(), $plan->getId());
	}

	public function testCreateWithPlan() {
		$plan = self::createTestPlan();
		$subscription = self::createTestSubscription();
		$plan->create();

		$subscription->setPlan($plan);
		$subscription->create();

		$this->validateSubscription($subscription);
		$this->assertTrue($subscription->getPlan()->getId());
		$this->assertTrue($plan->getId());

		$subscription2 = PagarMe_Subscription::findById($subscription->getId());
		$this->assertTrue($subscription2->getPlan());
		$this->assertEqual($subscription2->getPlan()->getId(), $plan->getId());

		$card = self::createTestCard();
		$subscription3 = new PagarMe_Subscription(array(
			'customer' => array(
				'email' => 'lala@lala.com',
			),
		));
		$subscription3->setPlan($plan);
		$subscription3->setCard($card);
		$subscription3->create();

		$this->assertTrue($subscription3->getId());

		$card->create();
		$subscription4 = new PagarMe_Subscription(array(
			'customer' => array(
				'email' => 'lala@lala.com',
			),
		));
		$subscription4->setPlan($plan);
		$subscription4->setCard($card);
		$subscription4->create();

		$this->assertTrue($subscription4->getId());

		$subscription4 = new PagarMe_Subscription(array(
			'card' => $card,
			'customer' => array(
				'email' => 'lala@lala.com',
			),
		));
		$subscription4->setPlan($plan);
		$subscription4->create();

		$this->assertTrue($subscription4->getId());
	}

	public function testCancel() {
		$subscription = self::createTestSubscription();
		$subscription->create();

		$subscription->cancel();
		$this->assertEqual($subscription->status, 'canceled');
	}

	public function testCharge() {
		$subscription = self::createTestSubscription();
		$subscription->create();
		$subscription->charge(3600);
		$transaction = $subscription->getCurrentTransaction();
		$this->assertEqual($transaction->getInstallments(), '1');
		$this->assertEqual($transaction->getAmount(), '3600');
	}

	public function testChargeWithInstallments() {
		$subscription = self::createTestSubscription();
		$subscription->create();
		$subscription->charge(3600, 3);
		$transaction = $subscription->getCurrentTransaction();
		$this->assertEqual($transaction->getAmount(), '3600');
		$this->assertEqual($transaction->getInstallments(), '3');
	}
}
