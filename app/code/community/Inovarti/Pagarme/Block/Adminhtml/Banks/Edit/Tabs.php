<?php


class Inovarti_Pagarme_Block_Adminhtml_Banks_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("banks_banks");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("pagarme")->__("Back Account Details"));
    }

    /**
     * @return Mage_Core_Block_Abstract
     * @throws Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("pagarme")->__("Back Account Details"),
            "title" => Mage::helper("pagarme")->__("Back Account Details"),
            "content" => $this->getLayout()->createBlock("pagarme/adminhtml_banks_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}