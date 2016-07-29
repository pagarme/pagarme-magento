<?php

$installer = new Mage_Catalog_Model_Resource_Setup('pagarme_setup');
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('pagarme_split_rules'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('split_rule_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Pagarme Split Rule Id')
    ->addColumn('recipient_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
    ), 'Recipient id.')
    ->addColumn('charge_processing_fee', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 1, array(
        'nullable' => false,
        'default' => 0,
    ), 'Status')
    ->addColumn('liable', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 1, array(
        'nullable' => false,
        'default' => 0,
    ), 'Sets whether the receiver linked to this rule will be responsible for transaction risk (chargeback)')
    ->addColumn('type_amount_charged', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
        'nullable'  => true,
    ), 'Percentage that the recipient will receive the transaction amount')
    ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
        'nullable'  => true,
    ), 'Value that the recipient will receive the transaction.')
    ->addColumn('shipping_charge', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 1, array(
        'nullable' => false,
        'default' => 0,
    ), 'Value that the recipient will receive the transaction.')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable'  => false,
    ), 'date time created row')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'nullable'  => false,
    ), 'date time updated row');

$installer->getConnection()->createTable($table);
$installer->endSetup();