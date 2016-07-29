<?php

class PagarMe_Model extends PagarMe_Object {
	protected static $root_url;

	public function __construct($response = array()) {
		parent::__construct($response);
	}

	public static function getUrl() {
		$class = get_called_class();
		$search = preg_match("/PagarMe_(.*)/",$class, $matches);
		return '/'. strtolower($matches[1]) . 's';
	}

	public function create() {
		try {
			$request = new PagarMe_Request(self::getUrl(), 'POST');
			$parameters = $this->__toArray(true);
			$request->setParameters($parameters);
			$response = $request->run();
			return $this->refresh($response);
		} catch(Exception $e) {
			throw new PagarMe_Exception($e->getMessage());
		}
	}

	public function save()
	{
		try {
			if(method_exists(get_called_class(), 'validate')) {
				if(!$this->validate()) return false;
			}
			$request = new PagarMe_Request(self::getUrl(). '/' . $this->id, 'PUT');
			$parameters = $this->unsavedArray();
			$request->setParameters($parameters);
			$response = $request->run();
			return $this->refresh($response);
		} catch(Exception $e) {
			throw new PagarMe_Exception($e->getMessage());
		}
	}


	public static function findById($id)
	{
		$request = new PagarMe_Request(self::getUrl() . '/' . $id, 'GET');
		$response = $request->run();
		$class = get_called_class();
		return new $class($response);
	}

	public static function all($page = 1, $count = 10)
	{
		$request = new PagarMe_Request(self::getUrl(), 'GET');
		$request->setParameters(array("page" => $page, "count" => $count));
		$response = $request->run();
		$return_array = Array();
		$class = get_called_class();
		foreach($response as $r) {
			$return_array[] = new $class($r);
		}

		return $return_array;
	}
}
