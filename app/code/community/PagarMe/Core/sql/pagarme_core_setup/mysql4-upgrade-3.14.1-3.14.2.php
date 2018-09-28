<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('pagarme_transaction');

$columnName = 'boleto_expiration_date';

$installer->getConnection()
    ->addColumn(
        $table,
        $columnName,
        [
            'type' => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            'comment' => 'Boleto expiration date timestamp',
            'nullable' => true
        ]
    );

$installer->endSetup();
