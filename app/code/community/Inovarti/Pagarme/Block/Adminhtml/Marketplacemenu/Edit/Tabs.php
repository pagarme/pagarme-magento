<?php


class Inovarti_Pagarme_Block_Adminhtml_Marketplacemenu_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("marketplacemenu_marketplacemenu");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("pagarme")->__("Marketplace Menu Details"));
    }

    /**
     * @return Mage_Core_Block_Abstract
     * @throws Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("pagarme")->__("Marketplace Menu Details"),
            "title" => Mage::helper("pagarme")->__("Marketplace Menu Details"),
            "content" => $this->getLayout()->createBlock("pagarme/adminhtml_marketplacemenu_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}