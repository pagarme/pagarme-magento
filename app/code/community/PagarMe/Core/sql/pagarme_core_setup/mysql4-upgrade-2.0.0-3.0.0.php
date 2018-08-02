<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('pagarme_transaction');

$columnName = 'reference_key';

$installer->getConnection()
    ->addColumn(
        $table,
        $columnName,
        [
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length' => 255,
            'comment' => 'A unique hash to relate an order to a transaction',
            'after' => 'transaction_id',
            'nullable' => true
        ]
    );

$installer->getConnection()
    ->addKey(
        $table,
        sprintf('unique_%s', $columnName),
        [$columnName],
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    );

$installer->endSetup();
