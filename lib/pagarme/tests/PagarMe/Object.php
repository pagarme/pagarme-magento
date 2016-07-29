<?php

class PagarMe_ObjectTest extends  PagarMeTestCase {
	public function testBuild() {
		$obj = self::createPagarMeObject();
		$this->assertTrue($obj instanceof PagarMe_Transaction);
		$this->assertTrue($obj->customer instanceof PagarMe_Customer);
	}

	public function testSet() {
		$obj = self::createPagarMeObject();
		$obj->abc = '123';

		$this->assertTrue($obj->abc == '123');
	}

	public function testArray() {
		$obj = self::createPagarMeObject();
		$this->assertTrue($obj['amount'] == 1590);
	}

	public function testForeach() {
		$obj = new PagarMe_Object(array('abc' => 'd', 'bcd' => 'e', 'vvvv' => '1234', 'bkg' => 4444, 'bsfs' => 555));
		$count = 0;

		foreach($obj as $k => $v) {
			$this->assertTrue($k);
			$this->assertTrue($v);
			$count++;
		}

		$this->assertTrue($count == 5);
	}

	public function testUnset() {
		$obj = self::createPagarMeObject();
		unset($obj['amount']);
		$this->assertFalse($obj['amount']);

		unset($obj->card_brand);
		$this->assertFalse($obj->card_brand);
	}
}
