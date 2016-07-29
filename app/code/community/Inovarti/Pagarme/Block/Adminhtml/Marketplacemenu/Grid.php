<?php

class Inovarti_Pagarme_Block_Adminhtml_Marketplacemenu_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId("pagarmeMarketplacemenuGrid");
        $this->setDefaultSort("entity_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $model = Mage::getModel('pagarme/marketplacemenu')->getCollection();
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

        $this->addColumn("sku", array(
            "header" => Mage::helper("pagarme")->__("Sku"),
            "align" => "right",
            "type" => "varchar",
            "index" => "sku",
            "sortable"  => false
        ));

        $this->addColumn("recipient_id", array(
            "header" => Mage::helper("pagarme")->__("Recipient Id"),
            "align" => "right",
            "type" =>   "text",
            "index" => "recipient_id",
            "sortable"  => false
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