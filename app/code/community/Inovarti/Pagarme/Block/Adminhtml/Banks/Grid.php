<?php

class Inovarti_Pagarme_Block_Adminhtml_Banks_Grid
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
        $this->setId("pagarmeBanksGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $this->pagarmeModel = PagarMe_Bank_Account::all(20, 0);
        $this->currentModel = Mage::getModel('pagarme/banks');

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

        $this->addColumn("legal_name", array(
            "header" => Mage::helper("pagarme")->__("Legal Name"),
            "align" => "right",
            "index" => "legal_name",
            "type" => "varchar",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("bank_code", array(
            "header" => Mage::helper("pagarme")->__("Bank Code"),
            "align" => "right",
            "index" => "bank_code",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("agency", array(
            "header" => Mage::helper("pagarme")->__("Agency"),
            "align" => "right",
            "type" => "number",
            "index" => "agency",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("agency_dv", array(
            "header" => Mage::helper("pagarme")->__("Agency Cv"),
            "align" => "right",
            "type" => "number",
            "index" => "agency_dv",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("account", array(
            "header" => Mage::helper("pagarme")->__("Account Number"),
            "align" => "right",
            "index" => "account",
            "type" => "number",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("account_dv", array(
            "header" => Mage::helper("pagarme")->__("Account dv"),
            "align" => "right",
            "type" => "number",
            "index" => "account_dv",
            "filter" => false,
            "sortable"  => false
        ));

        $this->addColumn("document_number", array(
            "header" => Mage::helper("pagarme")->__("Document Number"),
            "align" => "right",
            "type" => "number",
            "index" => "document_number",
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

        return parent::_prepareColumns();
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        //return $this->getUrl("*/*/edit", array("entity_id" => $row->getEntityId()));
        return null;
    }
}