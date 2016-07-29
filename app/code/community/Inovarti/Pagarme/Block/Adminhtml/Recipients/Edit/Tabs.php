<?php


class Inovarti_Pagarme_Block_Adminhtml_Recipients_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("recipients_recipients");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("pagarme")->__("Recipient Account Details"));
    }

    /**
     * @return Mage_Core_Block_Abstract
     * @throws Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("pagarme")->__("Recipient Account Details"),
            "title" => Mage::helper("pagarme")->__("Recipient Account Details"),
            "content" => $this->getLayout()->createBlock("pagarme/adminhtml_recipients_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}