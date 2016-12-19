<?php

$installer = new Mage_Sales_Model_Resource_Setup('pagarme_setup');
$installer->startSetup();

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
    ->newTable($installer->getTable('pagarme_split_rules_group_has_split_rule'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
        'primary' => false
            ), 'Id')
    ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
            ), 'Group ID')
    ->addColumn('split_rule_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false
            ), 'Split Rule ID');

$this->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('pagarme_split_rules_store_belogs_to_group'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
        'primary' => true
            ), 'Id')
    ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
        'primary' => false), 'Group ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
        'primary' => false), 'Store ID');

$this->getConnection()->createTable($table);

$installer->endSetup();
