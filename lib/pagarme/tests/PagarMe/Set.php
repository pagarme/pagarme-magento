<?php
class PagarMe_SetTest extends PagarMeTestCase {

	public function testAdd() {
		$set = self::createTestSet();

		$set->add('kkkkkkkk');
		$set->add('1234');

		$arr = $set->toArray();

		$this->assertTrue(in_array('key', $arr));
		$this->assertTrue(in_array('value', $arr));
		$this->assertTrue(in_array('1234', $arr));
		$this->assertTrue(in_array('abc', $arr));
		$this->assertTrue(in_array('bcd', $arr));
	}

	public function testIncludes() {
		$set = self::createTestSet();
		$this->assertTrue($set->includes('bcd'));
	}

	public function testRemove() {
		$set = self::createTestSet();
		$set->remove('key');
		$this->assertFalse($set->includes('key'));
	}

	public function testForeach() {
		$set = self::createTestSet();
		$count = 0;

		foreach($set as $k) {
			$this->assertTrue($k);
			$count++;
		}
		$this->assertTrue($count == 5);
	}
}
