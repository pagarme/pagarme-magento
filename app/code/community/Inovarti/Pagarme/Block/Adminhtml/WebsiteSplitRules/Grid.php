<?php

class Inovarti_Pagarme_Block_Adminhtml_WebsiteSplitRules_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('pagarmeWebsiteSplitRulesGrid');
        $this->setDefaultSort('main_table.entity_id');
        $this->setDefaultDir('DESC');
    }

    public function _prepareCollection() {
        $collection = Mage::getModel('pagarme/splitRulesGroup')
            ->getCollectionWithSplitRuleAmount();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function _prepareColumns() {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('pagarme')->__('ID'),
            'align'     => 'right',
            'width'     => '10%',
            'type'      => 'varchar',
            'index'     => 'entity_id',
            'sortable'  => false,
            'filter_index' => 'main_table.entity_id'
        ));

        $this->addColumn('website_name', array(
            'header'    => Mage::helper('pagarme')->__('Website'),
            'align'     => 'right',
            'type'      => 'varchar',
            'index'     => 'website_name',
            'sortable'  => false
        ));

        $this->addColumn('bbm_amount', array(
            'header'    => Mage::helper('pagarme')->__('BBM Amount'),
            'align'     => 'right',
            'type'      => 'varchar',
            'index'     => 'bbm_amount',
            'sortable'  => false
        ));

        $this->addColumn('split_rules_1_amount', array(
            'header'    => Mage::helper('pagarme')->__('Worldwine Amount'),
            'align'     => 'right',
            'type'      => 'varchar',
            'index'     => 'worldwine_amount',
            'sortable'  => false
        ));

        $this->addColumn('website_amount', array(
            'header'    => Mage::helper('pagarme')->__('Website Amount'),
            'align'     => 'right',
            'type'      => 'varchar',
            'index'     => 'website_amount',
            'sortable'  => false
        ));
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('entity_id' => $row->getEntityId()));
    }
}
