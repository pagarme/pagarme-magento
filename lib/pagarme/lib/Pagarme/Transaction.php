<?php

class PagarMe_Transaction extends PagarMe_TransactionCommon {

	public function charge()
	{
		$this->create();
	}

	public function capture($data = false)
	{
			$request = new PagarMe_Request(self::getUrl().'/'.$this->id . '/capture', 'POST');

			if(gettype($data) == 'array') {
				$request->setParameters($data);
			} else {
				if($data) {
					$request->setParameters(array('amount' => $data));
				}
			}

			$response = $request->run();
			$this->refresh($response);
	}

	public function refund($params = array())
	{
			$request = new PagarMe_Request(self::getUrl().'/'.$this->id . '/refund', 'POST');
			$request->setParameters($params);
			$response = $request->run();
			$this->refresh($response);
	}
}
