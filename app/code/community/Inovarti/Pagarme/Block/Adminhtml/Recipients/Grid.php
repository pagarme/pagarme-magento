<?php

class Inovarti_Pagarme_Block_Adminhtml_Recipients_Grid
    extends Inovarti_Pagarme_Block_Adminhtml_AbstractPagarme
{
    protected $collection;
    protected $pagarmeModel;
    protected $currentModel;

    /**
     * Inovarti_Pagarme_Block_Adminhtml_Banks_Grid constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId("pagarmeRecipientsGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $this->pagarmeModel = PagarMe_Recipient::all(20, 0);
        $this->currentModel = Mage::getModel('pagarme/recipients');

        $this->prepareCollection($this->pagarmeModel);
        $this->setCollection($this->collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn("id", array(
            "header" => Mage::helper("pagarme")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "varchar",
            "index" => "id",
            "sortable"  => false
        ));

        $this->addColumn("transfer_enabled", array(
            "header" => Mage::helper("pagarme")->__("Transfer Enable"),
            "align" => "right",
            "index" => "transfer_enabled",
            "type" => "options",
            "filter" => false,
            "sortable"  => false,
            "options" => Mage::getModel('adminhtml/system_config_source_yesno')->toArray()
        ));

        $this->addColumn("transfer_interval", array(
            "header" => Mage::helper("pagarme")->__("Transfer Interval"),
            "align" => "right",
            "type" => "varchar",
            "index" => "transfer_interval",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("transfer_day", array(
            "header" => Mage::helper("pagarme")->__("Transfer Day"),
            "align" => "right",
            "type" => "number",
            "index" => "transfer_day",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("bank_account_id", array(
            "header" => Mage::helper("pagarme")->__("Bank Account Id"),
            "align" => "right",
            "index" => "bank_account_id",
            "type" => "number",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("date_created", array(
            "header" => Mage::helper("pagarme")->__("Create At"),
            "align" => "right",
            "index" => "date_created",
            "type" =>   "datetime",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("date_updated", array(
            "header" => Mage::helper("pagarme")->__("Updated At"),
            "align" => "right",
            "index" => "date_updated",
            "type" =>   "datetime",
            "filter" => false,
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
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }
}