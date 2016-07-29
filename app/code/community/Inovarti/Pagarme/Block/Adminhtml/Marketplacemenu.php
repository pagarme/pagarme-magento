<?php

class Inovarti_Pagarme_Block_Adminhtml_Marketplacemenu
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks constructor.
     */
    public function __construct()
    {
        $this->_controller = "adminhtml_marketplacemenu";
        $this->_blockGroup = "pagarme";
        $this->_headerText = Mage::helper("pagarme")->__("Marketplace Menu Manager");
        $this->_addButtonLabel = Mage::helper("pagarme")->__("Add New product to menu marketplace");

        parent::__construct();
    }
}