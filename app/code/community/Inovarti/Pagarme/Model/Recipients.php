<?php

class Inovarti_Pagarme_Model_Recipients
    extends Mage_Core_Model_Abstract
{
    /**
     * Inovarti_Pagarme_Model_Recipients constructor.
     */
    protected function _construct()
    {
        return $this->_init('pagarme/recipients');
    }

    /**
     * Load object data
     *
     * @param   integer $id
     * @return  Mage_Core_Model_Abstract
     */
    public function load($id, $field=null)
    {
        $recipient = PagarMe_Recipient::findById($id);
        return $this->prepareVarienObject($recipient);
    }

    private function prepareVarienObject($recipient)
    {
        $accountObject = new Varien_Object();
        $accountObject->setId($recipient->getId());
        $accountObject->setTransferEnabled($recipient->getTransferEnabled());
        $accountObject->setLastTransfer($recipient->getLastTransfer());
        $accountObject->setTransferInterval($recipient->getTransferInterval());
        $accountObject->setTransferDay($recipient->getTransferDay());
        $accountObject->setDateCreated($recipient->getDateCreated());
        $accountObject->setDateUpdated($recipient->getDateUpdated());
        $accountObject->setBankAccountId($recipient->getBankAccount()->getId());

        return $accountObject;
    }

    /**
     * @param $collectionData
     * @return false|Mage_Core_Model_Abstract
     */
    public function getCollectionData($collectionData)
    {
        $collection = Mage::getModel('pagarme/ServiceVarienDataCollection');

        foreach ($collectionData as $recipient) {

            if (!$recipient) {
                return $collection;
            }

            $accountObject = new Varien_Object();
            $accountObject->setId($recipient->getId());
            $accountObject->setTransferEnabled($recipient->getTransferEnabled());
            $accountObject->setTransferInterval($recipient->getTransferInterval());
            $accountObject->setTransferDay($recipient->getTransferDay());
            $accountObject->setDateCreated($recipient->getDateCreated());
            $accountObject->setDateUpdated($recipient->getDateUpdated());
            $accountObject->setBankAccountId($recipient->getBankAccount()->getId());

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
            return PagarMe_Recipient::findById($data);
        } catch (Exception $e) {
            return null;
        }
    }
}