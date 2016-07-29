<?php

class Inovarti_Pagarme_Block_Adminhtml_Banks_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks_Edit constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "entity_id";
        $this->_blockGroup = "pagarme";
        $this->_controller = "adminhtml_banks";
        $this->_updateButton("save", "label", Mage::helper("pagarme")->__("Save Bank Account"));
        $this->_updateButton("delete", "label", Mage::helper("pagarme")->__("Delete Bank Account"));
        
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        if(Mage::registry("banks_data") && Mage::registry("banks_data")->getEntityId()) {
            return Mage::helper("pagarme")->__("Edit Banck Account '%s'", $this->htmlEscape(Mage::registry("banks_data")->getEntityId()));
        }

        return Mage::helper("pagarme")->__("Create Bank Account");
    }
}