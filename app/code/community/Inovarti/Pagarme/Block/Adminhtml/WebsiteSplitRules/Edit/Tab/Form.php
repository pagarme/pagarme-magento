<?php

class Inovarti_Pagarme_Block_Adminhtml_WebsiteSplitRules_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $formData = Mage::registry('websiteSplitRules_data');

        $websites = $this->getWebsites();
        $marketplaceRecipientId = Mage::getModel('pagarme/split')
            ->getMarketplaceRecipientId();

        $form = new Varien_Data_Form();

        $this->setForm($form);

        $fieldset = $form->addFieldset('ww_form', array('legend' => Mage::helper('pagarme')->__('Worldwine')));

        $fieldset->addField('split_rules_0_entity_id', 'hidden', array(
            'name'     => 'split_rules[0][entity_id]',
            'value'    => $formData['split_rules'][0]['entity_id']
        ));

        $fieldset->addField('split_rules_0_recipient_id', 'text', array(
            'label'    => Mage::helper('pagarme')->__('Recipient Id'),
            'name'     => 'split_rules[0][recipient_id]',
            'class'    => 'required-entry',
            'value'   => $formData['split_rules'][0]['recipient_id'] == null ? $marketplaceRecipientId : $formData['split_rules'][0]['recipient_id'],

            'required' => true
        ));

        $fieldset->addField('split_rules_0_charge_processing_fee', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Charge Processing Fee'),
            'name'    => 'split_rules[0][charge_processing_fee]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'value'   => $formData['split_rules'][0]['charge_processing_fee'],
            'required' => true
        ));

        $fieldset->addField('split_rules_0_liable', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Liable'),
            'name'    => 'split_rules[0][liable]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'value'   => $formData['split_rules'][0]['liable'],
            'required' => true
        ));

        $fieldset->addField('split_rules_0_amount', 'text', array(
            'label'   => Mage::helper('pagarme')->__('Amount (%)'),
            'name'    => 'split_rules[0][amount]',
            'class'   => 'required-entry',
            'value'   => $formData['split_rules'][0]['amount'],
            'required' => true
        ));

        $fieldset = $form->addFieldset('bbm_form', array('legend' => Mage::helper('pagarme')->__('BBM')));

        $fieldset->addField('split_rules_1_entity_id', 'hidden', array(
            'name'     => 'split_rules[1][entity_id]',
            'value'    => $formData['split_rules'][1]['entity_id']
        ));

        $fieldset->addField('split_rules_1_recipient_id', 'text', array(
            'label'    => Mage::helper('pagarme')->__('Recipient Id'),
            'name'     => 'split_rules[1][recipient_id]',
            'class'    => 'required-entry',
            'value'   => $formData['split_rules'][1]['recipient_id'],
            'required' => true
        ));

        $fieldset->addField('split_rules_1_charge_processing_fee', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Charge Processing Fee'),

            'name'    => 'split_rules[1][charge_processing_fee]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'value'   => $formData['split_rules'][1]['charge_processing_fee'],
            'required' => true
        ));

        $fieldset->addField('split_rules_1_liable', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Liable'),
            'name'    => 'split_rules[1][liable]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'value'   => $formData['split_rules'][1]['liable'],
            'required' => true
        ));

        $fieldset->addField('split_rules_1_amount', 'text', array(
            'label'   => Mage::helper('pagarme')->__('Amount (%)'),
            'name'    => 'split_rules[1][amount]',
            'class'   => 'required-entry',
            'value'   => $formData['split_rules'][1]['amount'],
            'required' => true
        ));

        $fieldset = $form->addFieldset('website_form', array('legend' => Mage::helper('pagarme')->__('Website')));

        $fieldset->addField('split_rules_2_entity_id', 'hidden', array(
            'name'     => 'split_rules[2][entity_id]',
            'value'    => $formData['split_rules'][2]['entity_id']
        ));

        $fieldset->addField('website_id', 'select', array(
            'label'    => Mage::helper('pagarme')->__('Loja'),
            'name'     => 'website_id',
            'class'    => 'required-entry',
            'options'  => $websites,
            'value'   => $formData['website_id'],
            'required' => true
        ));

        $fieldset->addField('split_rules_2_recipient_id', 'text', array(
            'label'    => Mage::helper('pagarme')->__('Recipient Id'),
            'name'     => 'split_rules[2][recipient_id]',
            'class'    => 'required-entry',
            'value'   => $formData['split_rules'][2]['recipient_id'],
            'required' => true
        ));

        $fieldset->addField('split_rules_2_charge_processing_fee', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Charge Processing Fee'),
            'name'    => 'split_rules[2][charge_processing_fee]',
            'class'   => 'required-entry',
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'value'   => $formData['split_rules'][2]['charge_processing_fee'],
            'required' => true
        ));

        $fieldset->addField('split_rules_2_liable', 'select', array(
            'label'   => Mage::helper('pagarme')->__('Liable'),
            'name'    => 'split_rules[2][liable]',
            'class'   => 'required-entry',
            'value'   => $formData['split_rules'][2]['liable'],
            'options' => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'required' => true
        ));

        $fieldset->addField('split_rules_2_amount', 'text', array(
            'label'   => Mage::helper('pagarme')->__('Amount (%)'),
            'name'    => 'split_rules[2][amount]',
            'class'   => 'required-entry',
            'value'   => $formData['split_rules'][2]['amount'],
            'required' => true
        ));

        if (Mage::getSingleton("adminhtml/session")->getSplitRulesGroupData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getSplitRulesGroupData());
            Mage::getSingleton("adminhtml/session")->setSplitRulesGroupData(null);
        }

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

    private function getWebsites()
    {
        $websites = array(
            '' => Mage::helper('pagarme')->__('Select')
        );


        foreach(Mage::app()->getWebsites() as $website)
        {
            $websites[$website->getWebsiteId()] = $website->getName();
        }

        return $websites;

    }
}
