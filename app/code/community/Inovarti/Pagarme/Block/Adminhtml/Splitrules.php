<?php

class Inovarti_Pagarme_Block_Adminhtml_Splitrules
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks constructor.
     */
    public function __construct()
    {
        $this->_controller = "adminhtml_splitrules";
        $this->_blockGroup = "pagarme";
        $this->_headerText = Mage::helper("pagarme")->__("Split Rules Manager");
        $this->_addButtonLabel = Mage::helper("pagarme")->__("Add New Split Rule");

        parent::__construct();
    }
}