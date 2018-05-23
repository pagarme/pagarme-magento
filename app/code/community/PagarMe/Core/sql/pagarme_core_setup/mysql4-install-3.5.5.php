<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('pagarme_transaction'))
    ->addColumn(
        'reference_key',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        [
            'nullable' => false,
            'primary'  => true
        ]
    )
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
    )
    ->addColumn(
        'installments',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        1,
        [
            'nullable' => false,
        ]
    )
    ->addColumn(
        'interest_rate',
        Varien_Db_Ddl_Table::TYPE_FLOAT,
        null
    )
    ->addColumn(
        'future_value',
        Varien_Db_Ddl_Table::TYPE_FLOAT,
        null
    )
    ->addColumn(
        'rate_amount',
        Varien_Db_Ddl_Table::TYPE_FLOAT,
        null
    )
    ->addColumn(
        'payment_method',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        50,
        [
            'nullable' => false
        ]
    );

$installer->getConnection()
    ->createTable($table);

$installer->endSetup();
