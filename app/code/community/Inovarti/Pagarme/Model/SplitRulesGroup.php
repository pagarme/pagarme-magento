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

    public function setStore(Mage_Core_Model_Store $store)
    {
        $this->store = $store;
    }

    public function save()
    {
        parent::save();

        $relationships = array();

        foreach ($this->splitRules as $splitRule) {
            $splitRule->save();
            $relationships[] = array('group_id' => $this->getId(), 'split_rule_id' => $splitRule->getId());
        }

        $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
        $groupAndSplitRuleRelationshipTable = Mage::getSingleton('core/resource')->getTableName('pagarme_split_rules_group_has_split_rule');

        $conn->insertMultiple($groupAndSplitRuleRelationshipTable, $relationships);

        $storeBelogsToSplitRulesGroupTable = Mage::getSingleton('core/resource')->getTableName('pagarme_split_rules_store_belogs_to_group');
        $conn->insert($storeBelogsToSplitRulesGroupTable, array('group_id' => $this->getId(), 'store_id' => $this->store->getId()));

    }
}
