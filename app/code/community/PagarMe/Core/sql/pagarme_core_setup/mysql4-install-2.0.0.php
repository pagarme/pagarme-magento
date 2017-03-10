<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('pagarme_transaction'))
    ->addColumn(
        'order_id',
        Varien_Db_Ddl_Table::TYPE_BIGINT,
        null,
        [
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true
        ]
    )
    ->addColumn(
        'transaction_id',
        Varien_Db_Ddl_Table::TYPE_BIGINT,
        null,
        [
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true
        ]
    );

$installer->getConnection()
    ->createTable($table);

$installer->endSetup();
