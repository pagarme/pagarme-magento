<?php

class Inovarti_Pagarme_Block_Adminhtml_SplitRules_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset("pagarme_form", array("legend"=>Mage::helper("pagarme")->__("Split Rules Details")));

        $fieldset->addField("recipient_id", "text", array(
            "label" => Mage::helper("pagarme")->__("Recipient Id"),
            "name" => "recipient_id",
            "required" => false
        ));

        $fieldset->addField("charge_processing_fee", "select", array(
            "label" => Mage::helper("pagarme")->__("Charge Processing Fee"),
            "name" => "charge_processing_fee",
            "class" => "required-entry",
            "required" => true,
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        $fieldset->addField("liable", "select", array(
            "label" => Mage::helper("pagarme")->__("Liable"),
            "name" => "liable",
            "required" => false,
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        $fieldset->addField("type_amount_charged", "select", array(
            "label" => Mage::helper("pagarme")->__("Split Type"),
            "name" => "type_amount_charged",
            "required" => false,
            "options" => array(
                'variable' => 'Variavel (%)'

            )
        ));

        $fieldset->addField("amount", "text", array(
            "label" => Mage::helper("pagarme")->__("Amount"),
            "name" => "amount",
            "required" => false
        ));

        $fieldset->addField("shipping_charge", "select", array(
            "label" => Mage::helper("pagarme")->__("Shipping Charge"),
            "name" => "shipping_charge",
            "class" => "required-entry",
            "required" => true,
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        if (Mage::getSingleton("adminhtml/session")->getSplitrulesData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getSplitrulesData());
            Mage::getSingleton("adminhtml/session")->setSplitrulesData(null);
        } elseif(Mage::registry("splitrules_data")) {
            $form->setValues(Mage::registry("splitrules_data")->getData());
        }

        return parent::_prepareForm();
    }
}