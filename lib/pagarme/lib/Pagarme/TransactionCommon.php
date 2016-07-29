<?php

class PagarMe_TransactionCommon extends PagarMe_CardHashCommon {
	public function __construct($response = array())
	{
		parent::__construct($response);
		if(!isset($this->payment_method)) {
			$this->payment_method = 'credit_card';
		}

		if(!isset($this->status)) {
			$this->status = 'local';
		}
	}

	protected function checkCard()
	{
		if ($this->card) {
			if (!$this->hasUnsavedCardAttributes()) {
				if ($this->card->id) {
					$this->card_id = $this->card->id;
				} else {
					$this->card_number = $this->card->card_number;
					$this->card_holder_name = $this->card->card_holder_name;
					$this->card_expiration_month = $this->card->card_expiration_month;
					$this->card_expiration_year = $this->card->card_expiration_year;
					$this->card_cvv = $this->card->card_cvv;
				}
			}
			unset($this->card);
		}
	}

	public function create()
	{
		$this->checkCard();
		parent::create();
	}

	public function save()
	{
		$this->checkCard();
		parent::save();
	}

	public static function calculateInstallmentsAmount($amount, $interest_rate, $max_installments, $freeInstallments = false)
	{
		$request = new PagarMe_Request(self::getUrl() . '/calculate_installments_amount', 'GET');
		$params = array('amount' 			=> $amount,
						'interest_rate' 	=> $interest_rate,
						'max_installments' 	=> $max_installments);
		if ($freeInstallments) {
			$params['free_installments'] = $freeInstallments;
		}
		$request->setParameters($params);
		$response = $request->run();

		return $response;
	}

	protected function shouldGenerateCardHash()
	{
		return $this->payment_method == 'credit_card' && !$this->card_id;
	}

	protected function hasUnsavedCardAttributes()
	{
		$hasUnsavedCardAttrbutes = $this->_unsavedAttributes->includes('card_number');

		return $hasUnsavedCardAttrbutes;
	}
}
