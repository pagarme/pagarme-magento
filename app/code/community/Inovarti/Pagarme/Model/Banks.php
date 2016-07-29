<?php

class Inovarti_Pagarme_Model_Banks extends Mage_Core_Model_Abstract
{
    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _construct()
    {
        return $this->_init('pagarme/banks');
    }

    /**
     * @param $collectionData
     * @return false|Mage_Core_Model_Abstract
     */
    public function getCollectionData($collectionData)
    {
        $collection = Mage::getModel('pagarme/ServiceVarienDataCollection');

        foreach ($collectionData as $account) {

            if (!$account) {
                return $collection;
            }

            $accountObject = new Varien_Object();
            $accountObject->setId($account->getId());
            $accountObject->setBankCode($account->getBankCode());
            $accountObject->setAgency($account->getAgencia());
            $accountObject->setAgencyDv($account->getAgenciaDv());
            $accountObject->setAccount($account->getConta());
            $accountObject->setAccountDv($account->getContaDv());
            $accountObject->setDocumentType($account->getDocumentType());
            $accountObject->setDocumentNumber($account->getDocumentNumber());
            $accountObject->setLegalName($account->getLegalName());
            $accountObject->setChargeTransferFees($account->getChargeTransferFees());
            $accountObject->setDateCreated($account->getDateCreated());

            $collection->addItem($accountObject);
        }

        return $collection;
    }

    /**
     * @param $data
     * @return false|Mage_Core_Model_Abstract|mixed
     */
    public function getById($data)
    {
        try {
            return PagarMe_Bank_Account::findById($data);
        } catch (Exception $e) {
            return null;
        }
    }
}