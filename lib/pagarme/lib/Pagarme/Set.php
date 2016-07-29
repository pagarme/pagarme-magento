<?php

class PagarMe_Set implements Iterator {

	private $_values;
	private $_orderedValues;
	private $_position;


	public function __construct(array $members = array()) {
		$this->_values =  Array();
		$this->_position = 0;
		$this->_orderedValues = Array();

		foreach($members as $m) {
			if(!isset($this->_values[$m])) {
				$this->_orderedValues[] = $m;
			}
			$this->_values[$m] = true;
		}
	}

	public function includes($member) {
		return isset($this->_values[$member]);
	}

	public function add($member) {
		$this->_values[$member] = true;
	}

	public function remove($member) {
		unset($this->_values[$member]);
	}

	public function toArray() {
		return array_keys($this->_values);
	}

	public function rewind() {
		$this->_position = 0;
	}

	public function current() {
		return $this->_orderedValues[$this->_position];
	}

	public function key() {
		return $this->_position;
	}

	public function next() {
		return ++$this->_position;
	}

	public function valid() {
		return isset($this->_orderedValues[$this->_position]);
	}
}
