<?php


class Inovarti_Pagarme_Block_Adminhtml_Splitrules_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("splitrules_splitrules");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("pagarme")->__("Split Rules Details"));
    }

    /**
     * @return Mage_Core_Block_Abstract
     * @throws Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("pagarme")->__("Split Rules Details"),
            "title" => Mage::helper("pagarme")->__("Split Rules Details"),
            "content" => $this->getLayout()->createBlock("pagarme/adminhtml_splitrules_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}