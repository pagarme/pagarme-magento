<?php

class Inovarti_Pagarme_Block_Adminhtml_Banks
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks constructor.
     */
    public function __construct()
    {
        $this->_controller = "adminhtml_banks";
        $this->_blockGroup = "pagarme";
        $this->_headerText = Mage::helper("pagarme")->__("Banks Manager");
        $this->_addButtonLabel = Mage::helper("pagarme")->__("Add New Bank");

        parent::__construct();
    }
}