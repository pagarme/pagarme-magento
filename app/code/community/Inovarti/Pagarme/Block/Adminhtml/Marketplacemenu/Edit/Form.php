<?php

class Inovarti_Pagarme_Block_Adminhtml_Marketplacemenu_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     * @throws Exception
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            "id" => "edit_form",
            "action" => $this->getUrl("*/*/save", array("entity_id" => $this->getRequest()->getParam("entity_id"))),
            "method" => "post",
            "enctype" =>"multipart/form-data",
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
