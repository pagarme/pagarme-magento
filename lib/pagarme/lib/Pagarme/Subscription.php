<?php

class PagarMe_Subscription extends PagarMe_TransactionCommon {

	public function create() {
		if($this->plan) {
			$this->plan_id = $this->plan->id;
			unset($this->plan);
		}
		parent::create();
	}

	public function save() {
		if($this->plan) {
			$this->plan_id = $this->plan->id;
			unset($this->plan);
		}
		parent::save();
	}

	public function getTransactions() {
			$request = new PagarMe_Request(self::getUrl() . '/' . $this->id . '/transactions', 'GET');
			$response = $request->run();
			$this->transactions = PagarMe_Util::convertToPagarMeObject($response);
			return $this->transactions;
	}

	public function cancel() {
			$request = new PagarMe_Request(self::getUrl() . '/' . $this->id . '/cancel', 'POST');
			$response = $request->run();
			$this->refresh($response);
	}

	public function charge($amount, $installments=1) {
			$this->amount = $amount;
			$this->installments = $installments;
			$request = new PagarMe_Request(self::getUrl(). '/' . $this->id . '/transactions', 'POST');
			$request->setParameters($this->unsavedArray());
			$response = $request->run();

			$request = new PagarMe_Request(self::getUrl() . '/' . $this->id, 'GET');
			$response = $request->run();
			$this->refresh($response);
	}
}
