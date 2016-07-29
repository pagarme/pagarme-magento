<?php

class PagarMe_RecipientTest extends PagarMeTestCase {
	public function testCreate() {
		$r = self::createTestRecipient();
		$r->create();
		
		$this->assertTrue($r->getId());

		$bank = $r->getBankAccount();
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

		$this->assertTrue($r->getTransferEnabled());
		$this->assertEqual($r->getLastTransfer(), NULL);
		$this->assertEqual($r->getTransferInterval(), "weekly");
		$this->assertEqual($r->getTransferDay(), "5");
		$this->assertTrue($r->getAutomaticAnticipationEnabled());
		$this->assertEqual($r->getAnticipatableVolumePercentage(), 85);
		$this->assertTrue($r->getDateCreated());
		$this->assertTrue($r->getDateUpdated());
	}
}

