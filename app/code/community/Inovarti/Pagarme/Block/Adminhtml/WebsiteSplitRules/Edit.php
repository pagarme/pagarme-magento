<?php

class Inovarti_Pagarme_Block_Adminhtml_WebsiteSplitRules_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        $this->_objectId      = 'entityId';
        $this->_blockGroup    = 'pagarme';
        $this->_controller    = 'adminhtml_websiteSplitRules';
        $this->_updateButton('delete', 'label', Mage::helper('pagarme')->__('Delete'));

        parent::__construct();
    }

    public function getHeaderText() {
        if(Mage::registry('websiteSplitRules_data') && Mage::registry('websiteSplitRules_data')['entity_id']) {
            return Mage::helper('pagarme')->__('Edit Split Rule \'%s\'', $this->getEntityId());
        }

        return Mage::helper('pagarme')->__('Create Split Rule');
    }
}
