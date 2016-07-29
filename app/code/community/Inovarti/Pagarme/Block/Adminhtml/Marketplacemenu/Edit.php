<?php

class Inovarti_Pagarme_Block_Adminhtml_Marketplacemenu_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks_Edit constructor.
     */
    public function __construct()
    {
        $this->_objectId = "entity_id";
        $this->_blockGroup = "pagarme";
        $this->_controller = "adminhtml_marketplacemenu";
        $this->_updateButton("save", "label", Mage::helper("pagarme")->__("Save Marketplace Menu"));
        $this->_updateButton("delete", "label", Mage::helper("pagarme")->__("Delete Marketplace Menu"));
        parent::__construct();
        
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        if(Mage::registry("marketplacemenu_data") && Mage::registry("marketplacemenu_data")->getEntityId()) {
            return Mage::helper("pagarme")->__("Edit Marketplace Menu Row '%s'", $this->htmlEscape(Mage::registry("marketplacemenu_data")->getEntityId()));
        }

        return Mage::helper("pagarme")->__("Create Marketplace Menu Row");
    }
}