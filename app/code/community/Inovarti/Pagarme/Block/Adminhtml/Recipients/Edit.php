<?php

class Inovarti_Pagarme_Block_Adminhtml_Recipients_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks_Edit constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = "pagarme";
        $this->_controller = "adminhtml_recipients";
        $this->_updateButton("save", "label", Mage::helper("pagarme")->__("Update Recipient"));
        $this->_removeButton('delete');
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        if(Mage::registry("banks_data") && Mage::registry("recipients_data")->getEntityId()) {
            return Mage::helper("pagarme")->__("Edit Recipient '%s'", $this->htmlEscape(Mage::registry("recipients_data")->getEntityId()));
        }

        return Mage::helper("pagarme")->__("Edit Recipient");
    }
}
