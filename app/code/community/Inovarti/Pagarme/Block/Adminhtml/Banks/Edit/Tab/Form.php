<?php

class Inovarti_Pagarme_Block_Adminhtml_Banks_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset("pagarme_form", array("legend"=>Mage::helper("pagarme")->__("Bank Details")));

        $fieldset->addField("bank_code", "text", array(
            "label" => Mage::helper("pagarme")->__("Bank Code"),
            "name" => "bank_code",
            "class" => "required-entry",
            "required" => true
        ));

        $fieldset->addField("agency", "text", array(
            "label" => Mage::helper("pagarme")->__("Agency"),
            "name" => "agency",
            "class" => "required-entry",
            "required" => true
        ));

        $fieldset->addField("agency_dv", "text", array(
            "label" => Mage::helper("pagarme")->__("Agency DV"),
            "name" => "agency_dv",
            "required" => false
        ));

        $fieldset->addField("account_number", "text", array(
            "label" => Mage::helper("pagarme")->__("Account Number"),
            "name" => "account_number",
            "class" => "required-entry",
            "required" => true
        ));

        $fieldset->addField("account_dv", "text", array(
            "label" => Mage::helper("pagarme")->__("Account DV"),
            "name" => "account_dv",
            "class" => "required-entry",
            "required" => true
        ));

        $fieldset->addField("document_number", "text", array(
            "label" => Mage::helper("pagarme")->__("Document Number"),
            "name" => "document_number",
            "class" => "required-entry",
            "required" => true
        ));

        $fieldset->addField("legal_name", "text", array(
            "label" => Mage::helper("pagarme")->__("Legal Name"),
            "name" => "legal_name",
            "class" => "required-entry",
            "required" => true
        ));

        if (Mage::getSingleton("adminhtml/session")->getBanksData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getBanksData());
            Mage::getSingleton("adminhtml/session")->setBanksData(null);
        } elseif(Mage::registry("banks_data")) {
            $form->setValues(Mage::registry("banks_data")->getData());
        }

        return parent::_prepareForm();
    }
}