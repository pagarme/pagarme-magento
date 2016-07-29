<?php

class Inovarti_Pagarme_Block_Adminhtml_Splitrules_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks_Edit constructor.
     */
    public function __construct()
    {
        $this->_objectId = "entity_id";
        $this->_blockGroup = "pagarme";
        $this->_controller = "adminhtml_splitrules";
        $this->_updateButton("save", "label", Mage::helper("pagarme")->__("Save Split Rule"));
        $this->_updateButton("delete", "label", Mage::helper("pagarme")->__("Delete Split Rule"));

        parent::__construct();
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        if(Mage::registry("splitrules_data") && Mage::registry("splitrules_data")->getEntityId()) {
            return Mage::helper("pagarme")->__("Edit Split Rule '%s'", $this->htmlEscape(Mage::registry("splitrules_data")->getEntityId()));
        }

        return Mage::helper("pagarme")->__("Create Split Rule");
    }
}