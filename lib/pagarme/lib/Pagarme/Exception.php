<?php

class PagarMe_Exception extends Exception {
	protected $url, $method, $return_code, $parameter_name, $type, $errors;

	// Builds with a message and a response from the server
	public function __construct($message = null, $response_error = null)
	{
		$this->url = (isset($response_error['url'])) ? $response_error['url'] : null;
		$this->method = (isset($response_error['method'])) ? $response_error['method'] : null;

		if(isset($response_error['errors'])) {
			foreach($response_error['errors'] as $error) {
				$this->errors[] = new PagarMe_Error($error);
			}
		}

		parent::__construct($message);
	}


	// Builds an exception based on an error object
	public static function buildWithError($error) {
		$instance = new self($error->getMessage());
		return $instance;
	}

	// Builds an exception with the server response and joins all the errors
	public static function buildWithFullMessage($response_error) {
		$joined_messages = '';

		foreach($response_error['errors'] as $error) {
			$joined_messages .= $error['message'] . "\n";
		}

		$instance =  new self($joined_messages, $response_error);
		return $instance;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getReturnCode()
	{
		return $this->return_code;
	}
}
