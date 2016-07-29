<?php

$installer = new Mage_Sales_Model_Resource_Setup('core_setup');
$installer->startSetup();

$entities = array(
    'quote_item',
    'order_item'
);

foreach ($entities as $entity) {
    $installer->addAttribute($entity, 'recipient_id', array(
        'type'     => Varien_Db_Ddl_Table::TYPE_VARCHAR,
        'visible'  => true,
        'required' => false
    ));
}

$installer->endSetup();