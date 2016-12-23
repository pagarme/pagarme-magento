<?php

class Inovarti_Pagarme_Block_Adminhtml_WebsiteSplitRules_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('website_split_Rules');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('pagarme')->__('Split details'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'    => Mage::helper('pagarme')->__('Details'),
            'title'    => Mage::helper('pagarme')->__('Details'),
            'content'  => $this->getLayout()->createBlock('pagarme/adminhtml_websiteSplitRules_edit_tab_form')->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}
