<?php

$installer = new Mage_Sales_Model_Resource_Setup('pagarme_setup');
$installer->startSetup();

$table = $installer->getTable('pagarme/split_rules');

$installer->getConnection()
    ->addColumn($table, 'group_id', array(
        'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned' => true,
        'nullable' => true,
        'comment'  => 'Group ID'
    ));

$table = $installer->getConnection()
    ->newTable($installer->getTable('pagarme_split_rules_group'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
        'primary' => true
            ), 'Id')
    ->addColumn('group_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
            ), 'Group Name');

$this->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('pagarme_website_rules_stores'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
        'primary' => true), 'Id')
    ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
        'primary' => false), 'Group ID')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
        'primary' => false), 'Website ID');

$this->getConnection()->createTable($table);

$installer->endSetup();
