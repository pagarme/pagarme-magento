<?php

class Inovarti_Pagarme_Block_Adminhtml_Splitrules_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId("pagarmeSplitrulesGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $model = Mage::getModel('pagarme/splitrules')->getCollection();
        $this->setCollection($model);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn("entity_id", array(
            "header" => Mage::helper("pagarme")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "varchar",
            "index" => "entity_id",
            "sortable"  => false
        ));

        $this->addColumn("recipient_id", array(
            "header" => Mage::helper("pagarme")->__("Recipient Id"),
            "align" => "right",
            "type" => "varchar",
            "index" => "recipient_id"
        ));

        $this->addColumn("charge_processing_fee", array(
            "header" => Mage::helper("pagarme")->__("Charge Processing Fee"),
            "align" => "right",
            "type" =>   "options",
            "index" => "charge_processing_fee",
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        $this->addColumn("liable", array(
            "header" => Mage::helper("pagarme")->__("Liable"),
            "align" => "right",
            "index" => "liable",
            "type" => "options",
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        $this->addColumn("type_amount_charged", array(
            "header" => Mage::helper("pagarme")->__("Type Amount Charged"),
            "align" => "right",
            "index" => "type_amount_charged",
            "type" => "options",
            "options" => array(
                'fixed' => Mage::helper("pagarme")->__("Fixed"),
                'variable' => Mage::helper("pagarme")->__("Variable")
            )
        ));

        $this->addColumn("amount", array(
            "header" => Mage::helper("pagarme")->__("Amount"),
            "align" => "right",
            "index" => "amount",
            "type" =>   "varchar"
        ));

        $this->addColumn("shipping_charge", array(
            "header" => Mage::helper("pagarme")->__("Shipping Charge"),
            "align" => "right",
            "index" => "shipping_charge",
            "type" =>   "options",
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        $this->addColumn("created_at", array(
            "header" => Mage::helper("pagarme")->__("Created At"),
            "align" => "right",
            "index" => "created_at",
            "type" =>   "datetime"
        ));

        $this->addColumn("updated_at", array(
            "header" => Mage::helper("pagarme")->__("Updated At"),
            "align" => "right",
            "index" => "updated_at",
            "type" =>   "datetime"
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array("entity_id" => $row->getEntityId()));
    }
}