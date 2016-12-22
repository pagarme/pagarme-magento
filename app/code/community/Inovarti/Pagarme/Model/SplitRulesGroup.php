<?php

class Inovarti_Pagarme_Model_SplitRulesGroup extends Mage_Core_Model_Abstract
{
    private $store = null;
    private $splitRules = array();

    protected function _construct()
    {
        return $this->_init('pagarme/splitRulesGroup');
    }

    public function addSplitRule(Inovarti_Pagarme_Model_Splitrules $splitRule)
    {
        $this->splitRules[] = $splitRule;
    }

    public function getSplitRules()
    {
        return $this->splitRules;
    }

    public function getStore()
    {
        return $this->store;
    }

    public function setStore(Mage_Core_Model_Store $store)
    {
        $this->store = $store;
    }

    public function validate()
    {
        $errors = array();

        $amount = 0;
        $hasResponsibleForChargeProcessingFee = false;
        $hasResponsibleForLable = false;

        foreach($this->getSplitRules() as $splitRule) {
            $amount += $splitRule->getAmount();

            if(!$hasResponsibleForChargeProcessingFee)
                $hasResponsibleForChargeProcessingFee = ($splitRule->getChargeProcessingFee() == true);


            if(!$hasResponsibleForChargeback)
                $hasResponsibleForChargeback = ($splitRule->getLiable() == true);
        }

        if($amount != 100) {
            $errors[] = 'Amount < 100';
        }

        if(!$hasResponsibleForChargeProcessingFee)
            $errors[] = 'Ninguém é responsável pela taxa do pagarme';

        if(!$hasResponsibleForChargeback)
            $errors[] = 'Ninguém é responsável pelo chargeback';

        $splitRulesGroup = Mage::getModel('pagarme/splitRulesGroup')
            ->load($this->getStore()->getId(), 'store_id');

        if($splitRulesGroup->getId() > 0 && $splitRulesGroup->getId() != $this->getId())
            $errors[] = 'Essa loja já possui uma regra de split';

        return $errors;
    }

    public function save()
    {
        $this->setStoreId($this->getStore()->getId());
        $originalReturn = parent::save();

        $relationships = array();

        foreach ($this->splitRules as $splitRule) {
            $splitRule->setGroupId($this->getId());
            $splitRule->save();
        }

        return $originalReturn;
    }

    public function delete()
    {
        $splitRules = Mage::getModel('pagarme/splitrules')
            ->getCollection()
            ->addFieldToFilter('group_id', $this->getId());

        foreach($splitRules as $splitRule) {
            $splitRule->delete();
        }

        return parent::delete();
    }
}
