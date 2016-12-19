<?php

class Inovarti_Pagarme_Block_Adminhtml_StoreSplitRules_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('pagarmeStoreSplitRulesGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
    }

    public function _prepareCollection() {
        $model = Mage::getModel('pagarme/splitRulesGroup')->getCollection();
        $this->setCollection($model);
        return parent::_prepareCollection();
    }

    public function _prepareColumns() {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('pagarme')->__('ID'),
            'align'     => 'right',
            'width'     => '10%',
            'type'      => 'varchar',
            'index'     => 'entity_id',
            'sortable'  => false
        ));

        $this->addColumn('group_name', array(
            'header'    => Mage::helper('pagarme')->__('Split Rules Title'),
            'align'     => 'right',
            'type'      => 'varchar',
            'index'     => 'group_name',
            'sortable'  => false
        ));
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('entity_id' => $row->getEntityId()));
    }
}
