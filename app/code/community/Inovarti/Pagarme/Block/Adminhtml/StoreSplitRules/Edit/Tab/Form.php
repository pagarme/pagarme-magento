<?php

class Inovarti_Pagarme_Block_Adminhtml_StoreSplitRules_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('pagarme_form', array('legend' => Mage::helper('pagarme')->__('Store Split Rules Detail')));

        $fieldset->addField('group_name', 'text', array(
            'label'    => Mage::helper('pagarme')->__('Name'),
            'name'     => 'group_name',
            'class'    => 'required-entry',
            'required' => true
        ));

        if (Mage::getSingleton("adminhtml/session")->getSplitRulesGroupData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getSplitRulesGroupData());
            Mage::getSingleton("adminhtml/session")->setSplitRulesGroupData(null);
        } elseif(Mage::registry("splitRulesGroup_data")) {
            $form->setValues(Mage::registry("splitRulesGroup_data")->getData());
        }

        return parent::_prepareForm();
    }
}
