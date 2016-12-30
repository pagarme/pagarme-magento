<?php

class Inovarti_Pagarme_Model_SplitRulesGroup extends Mage_Core_Model_Abstract
{
    private $website = null;
    private $splitRules = array();

    protected function _construct()
    {
        return $this->_init('pagarme/splitRulesGroup');
    }

    public function addSplitRule(Inovarti_Pagarme_Model_Splitrules $splitRule)
    {
        $this->splitRules[] = $splitRule;
    }

    public function setSplitRules(Varien_Data_Collection $splitRules)
    {
        $this->splitRules = $splitRules;
    }

    public function getSplitRules()
    {
        return $this->splitRules;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite(Mage_Core_Model_Website $website)
    {
        $this->website = $website;
    }

    public function loadByWebsite(Mage_Core_Model_Website $website)
    {
        $splitRulesGroup = Mage::getModel('pagarme/splitRulesGroup')
            ->load($website->getId(), 'website_id');

        $splitRules = Mage::getModel('pagarme/splitrules')
            ->getCollection()
            ->addFieldToFilter('group_id', $splitRulesGroup->getId());

        $splitRulesGroup->setSplitRules($splitRules);

        return $splitRulesGroup;
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
            $errors[] = 'The sum of amount must be 100%.';
        }

        if(!$hasResponsibleForChargeProcessingFee)
            $errors[] = 'At least one recipient must be responsible for charge processing fee.';

        if(!$hasResponsibleForChargeback)
            $errors[] = 'At least one recipient must be liable.';

        $splitRulesGroup = Mage::getModel('pagarme/splitRulesGroup')
            ->load($this->getWebsite()->getId(), 'website_id');

        if($splitRulesGroup->getId() > 0 && $splitRulesGroup->getId() != $this->getId())
            $errors[] = 'Essa loja já possui uma regra de split';

        return $errors;
    }

    public function save()
    {
        $this->setWebsiteId($this->getWebsite()->getId());
        $originalReturn = parent::save();

        foreach ($this->splitRules as $splitRule) {
            if($splitRule->getId() < 1)
                $splitRule->setId(null);

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

    public function getCollectionWithSplitRuleAmount()
    {
        $tableName = Mage::getSingleton('core/resource')->getTableName('pagarme_split_rules');

        $subqueryTemplate = "(SELECT amount from {$tableName} sr WHERE sr.group_id = main_table.entity_id ORDER BY sr.entity_id LIMIT %d,1)";

        $bbmAmountSubquery = new Zend_Db_Expr(sprintf($subqueryTemplate, 0));
        $worldwineAmountSubquery = new Zend_Db_Expr(sprintf($subqueryTemplate, 1));
        $websiteAmountSubquery = new Zend_Db_Expr(sprintf($subqueryTemplate, 2));

        $collection = $this->getCollection();
        $collection->getSelect()
            ->join(array('website' => Mage::getConfig()->getTablePrefix() . 'core_website'), 'website.website_id = main_table.website_id')
            ->columns(array(
                'bbm_amount' => $bbmAmountSubquery,
                'worldwine_amount' => $worldwineAmountSubquery,
                'website_amount' => $websiteAmountSubquery,
                'website_name' => 'website.name'
            ));

        return $collection;
    }
}
