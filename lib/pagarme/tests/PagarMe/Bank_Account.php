<?php

class PagarMe_Bank_AccountTest extends PagarMeTestCase {
	public function testCreate() {
		$bank = self::createTestBankAccount();
		$bank->create();

		$this->assertTrue($bank->getId());
		$this->assertEqual($bank->getBankCode(), "341");
		$this->assertEqual($bank->getAgencia(), "0932");
		$this->assertEqual($bank->getAgenciaDv(), "5");
		$this->assertEqual($bank->getConta(), "58054");
		$this->assertEqual($bank->getContaDv(), "1");
		$this->assertEqual($bank->getDocumentType(), "cpf");
		$this->assertEqual($bank->getDocumentNumber(), "26268738888");
		$this->assertEqual($bank->getLegalName(), "API BANK ACCOUNT");
		$this->assertTrue($bank->getChargeTransferFees());
		$this->assertTrue($bank->getDateCreated());
	}
}
