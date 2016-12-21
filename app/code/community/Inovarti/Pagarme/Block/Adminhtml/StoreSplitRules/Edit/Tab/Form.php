<?php

class Inovarti_Pagarme_Block_Adminhtml_StoreSplitRules_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $stores = $this->getStores();
        array_unshift($stores, array('' => 'Select'));

        $recipients = $this->getRecipients();
        array_unshift($recipients, array('' => 'Select'));

        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('ww_form', array('legend' => Mage::helper('pagarme')->__('Worldwine')));

        $fieldset->addField('split_rules_0_recipient_id', 'select', array(
            'label'    => Mage::helper('pagarme')->__('Recebedor'),
            'name'     => 'split_rules[0][recipient_id]',
            'class'    => 'required-entry',
            'options'  => $recipients,
            'required' => true
        ));

        $fieldset->addField('split_rules_0_charge_processing_fee', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Charge Processing Fee'),
            'name'    => 'split_rules[1][charge_processing_fee]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'required' => true
        ));

        $fieldset->addField('split_rules_0_liable', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Liable'),
            'name'    => 'split_rules[0][liable]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'required' => true
        ));

        $fieldset->addField('split_rules_0_amount', 'text', array(
            'label'   => Mage::helper('pagarme')->__('Amount (%)'),
            'name'    => 'split_rules[0][amount]',
            'class'   => 'required-entry',
            'required' => true
        ));

        $fieldset = $form->addFieldset('bbm_form', array('legend' => Mage::helper('pagarme')->__('BBM')));

        $fieldset->addField('split_rules_1_recipient_id', 'select', array(
            'label'    => Mage::helper('pagarme')->__('Recebedor'),
            'name'     => 'split_rules[1][recipient_id]',
            'class'    => 'required-entry',
            'options'  => $recipients,
            'required' => true
        ));

        $fieldset->addField('split_rules_1_charge_processing_fee', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Charge Processing Fee'),
            'name'    => 'split_rules[1][charge_processing_fee]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'required' => true
        ));

        $fieldset->addField('split_rules_1_liable', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Liable'),
            'name'    => 'split_rules[1][liable]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'required' => true
        ));

        $fieldset->addField('split_rules_1_amount', 'text', array(
            'label'   => Mage::helper('pagarme')->__('Amount (%)'),
            'name'    => 'split_rules[1][amount]',
            'class'   => 'required-entry',
            'required' => true
        ));

        $fieldset = $form->addFieldset('store_form', array('legend' => Mage::helper('pagarme')->__('Store')));

        $fieldset->addField('store_id', 'select', array(
            'label'    => Mage::helper('pagarme')->__('Loja'),
            'name'     => 'store_id',
            'class'    => 'required-entry',
            'options'  => $stores,
            'required' => true
        ));

        $fieldset->addField('split_rules_2_recipient_id', 'select', array(
            'label'    => Mage::helper('pagarme')->__('Recipient'),
            'name'     => 'split_rules[2][recipient_id]',
            'class'    => 'required-entry',
            'options'  => $recipients,
            'required' => true
        ));

        $fieldset->addField('split_rules_2_charge_processing_fee', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Charge Processing Fee'),
            'name'    => 'split_rules[2][charge_processing_fee]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'required' => true
        ));

        $fieldset->addField('split_rules_2_liable', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Liable'),
            'name'    => 'split_rules[2][liable]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'required' => true
        ));

        $fieldset->addField('split_rules_2_amount', 'text', array(
            'label'   => Mage::helper('pagarme')->__('Amount (%)'),
            'name'    => 'split_rules[2][amount]',
            'class'   => 'required-entry',
            'required' => true
        ));

        if (Mage::getSingleton("adminhtml/session")->getSplitRulesGroupData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getSplitRulesGroupData());
            Mage::getSingleton("adminhtml/session")->setSplitRulesGroupData(null);
        } elseif (Mage::registry("splitRulesGroup_data")) {
            $form->setValues(Mage::registry("splitRulesGroup_data")->getData());
        }

        $this->getStores();

        return parent::_prepareForm();
    }

    private function getRecipients()
    {
        $recipients = array();

        foreach (PagarMe_Recipient::all() as $recipient) {
            $recipients[$recipient->getId()] = $recipient->getBankAccount()->getLegalName();
        }

        return $recipients;
    }

    private function getStores()
    {
        $stores = array();
        foreach(Mage::app()->getStores() as $store)
        {
            $stores[$store->getId()] = $store->getName();
        }

        return $stores;
    }
}
