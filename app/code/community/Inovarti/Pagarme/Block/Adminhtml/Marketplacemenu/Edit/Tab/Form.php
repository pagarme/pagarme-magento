<?php

class Inovarti_Pagarme_Block_Adminhtml_Marketplacemenu_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset("pagarme_form", array("legend"=>Mage::helper("pagarme")->__("Marketplace Menu Details")));

        $fieldset->addField("sku", "text", array(
            "label" => Mage::helper("pagarme")->__("Sku"),
            "name" => "sku",
            "required" => false
        ));

        $fieldset->addField("recipient_id", "text", array(
            "label" => Mage::helper("pagarme")->__("Recipient Id"),
            "name" => "recipient_id",
            "class" => "required-entry",
            "required" => true,
        ));

        if (Mage::getSingleton("adminhtml/session")->getMarketplacemenuData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getMarketplacemenuData());
            Mage::getSingleton("adminhtml/session")->geMarketplacemenuData(null);
        } elseif(Mage::registry("marketplacemenu_data")) {
            $form->setValues(Mage::registry("marketplacemenu_data")->getData());
        }

        return parent::_prepareForm();
    }
}