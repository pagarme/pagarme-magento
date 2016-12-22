<?php

class Inovarti_Pagarme_Block_Adminhtml_StoreSplitRules_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        $this->_objectId      = 'entityId';
        $this->_blockGroup    = 'pagarme';
        $this->_controller    = 'adminhtml_storeSplitRules';
        parent::__construct();
    }

    public function getHeaderText() {
        if(Mage::registry('storeSplitRules_data') && Mage::registry('storeSplitRules_data')['entity_id']) {
            return Mage::helper('pagarme')->__('Edit Split Rule \'%s\'', $this->getEntityId());
        }

        return Mage::helper('pagarme')->__('Create Split Rule');
    }
}
