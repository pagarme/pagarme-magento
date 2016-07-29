<?php

class Inovarti_Pagarme_Block_Adminhtml_Recipients_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset("pagarme_form", array("legend"=>Mage::helper("pagarme")->__("Recipient Details")));

        $fieldset->addField("transfer_enabled", "select", array(
            "label" => Mage::helper("pagarme")->__("Transfer Enabled"),
            "name" => "transfer_enabled",
            "class" => "required-entry",
            "required" => true,
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        $fieldset->addField("transfer_interval", "select", array(
            "label" => Mage::helper("pagarme")->__("Transfer Interval"),
            "name" => "transfer_enabled",
            "class" => "required-entry",
            "required" => true,
            "options" => array(
                'daily'     => Mage::helper("pagarme")->__("Daily"),
                'weekly'    => Mage::helper("pagarme")->__("Weekly"),
                'monthly'   => Mage::helper("pagarme")->__("Monthly")
            )
        ));

        $fieldset->addField("transfer_day", "text", array(
            "label" => Mage::helper("pagarme")->__("Transfer Day"),
            "name" => "transfer_day",
            "class" => "required-entry",
            "required" => true
        ));

        $fieldset->addField("bank_account_id", "text", array(
            "label" => Mage::helper("pagarme")->__("Banck Account ID"),
            "name" => "bank_account_id",
            "class" => "required-entry",
            "required" => true
        ));

        if (Mage::getSingleton("adminhtml/session")->getBanksData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getBanksData());
            Mage::getSingleton("adminhtml/session")->setBanksData(null);
        } elseif(Mage::registry("recipients_data")) {
            $form->setValues(Mage::registry("recipients_data")->getData());
        }

        return parent::_prepareForm();
    }
}