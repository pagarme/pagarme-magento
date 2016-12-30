<?php

class Inovarti_Pagarme_Block_Adminhtml_WebsiteSplitRules_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        $this->_objectId      = 'entity_id';
        $this->_blockGroup    = 'pagarme';
        $this->_controller    = 'adminhtml_websiteSplitRules';

        parent::__construct();
    }

    public function getHeaderText() {
        $splitRulesData = Mage::registry('websiteSplitRules_data');

        if($splitRulesData && $splitRulesData['entity_id']) {
            return Mage::helper('pagarme')->__("Edit Split Rule '%s'", $this->htmlEscape($splitRulesData['entity_id']));
        }

        return Mage::helper('pagarme')->__('Create Split Rule');
    }
}
