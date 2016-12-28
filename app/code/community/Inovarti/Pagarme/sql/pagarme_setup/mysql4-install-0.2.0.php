<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Pagarme
 * @author     Suporte <suporte@inovarti.com.br>
 *
 * UPDATED:
 *
 * @copyright  Copyright (C) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */
$installer = new Mage_Sales_Model_Resource_Setup('pagarme_setup');
$installer->startSetup();

// Quote Payment
$entity = 'quote_payment';
$attributes = array(
    'pagarme_card_hash' => array('type' => Varien_Db_Ddl_Table::TYPE_TEXT),
    'installments' => array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT),
    'installment_description' => array('type' => Varien_Db_Ddl_Table::TYPE_VARCHAR),
);

foreach ($attributes as $attribute => $options) {
    $installer->addAttribute($entity, $attribute, $options);
}

// Order Payment
$entity = 'order_payment';
$attributes = array(
    'installments' => array('type' => Varien_Db_Ddl_Table::TYPE_SMALLINT),
    'installment_description' => array('type' => Varien_Db_Ddl_Table::TYPE_VARCHAR),
    'pagarme_transaction_id' => array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER),
    'pagarme_boleto_url' => array('type' => Varien_Db_Ddl_Table::TYPE_VARCHAR),
    'pagarme_boleto_barcode' => array('type' => Varien_Db_Ddl_Table::TYPE_VARCHAR),
    'pagarme_boleto_expiration_date' => array('type' => Varien_Db_Ddl_Table::TYPE_DATETIME),
    'pagarme_antifraud_score' => array('type' => Varien_Db_Ddl_Table::TYPE_DECIMAL)
);

foreach ($attributes as $attribute => $options) {
    $installer->addAttribute($entity, $attribute, $options);
}

$table = $installer->getTable('sales/quote_address');

$installer->getConnection()
    ->addColumn($table, 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
    ));

$installer->getConnection()
    ->addColumn($table, 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
    ));

$table = $installer->getTable('sales/order');

$installer->getConnection()
    ->addColumn($table, 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
    ));

$installer->getConnection()
    ->addColumn($table, 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
    ));

$installer->getConnection()
    ->addColumn($table, 'fee_amount_invoiced', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount Invoiced',
    ));

$installer->getConnection()
    ->addColumn($table, 'base_fee_amount_invoiced', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount Invoiced',
    ));

$installer->getConnection()
    ->addColumn($table, 'fee_amount_refunded', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount Refunded',
    ));

$installer->getConnection()
    ->addColumn($table, 'base_fee_amount_refunded', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount Refunded',
    ));

$table = $installer->getTable('sales/invoice');

$installer->getConnection()
    ->addColumn($table, 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
    ));

$installer->getConnection()
    ->addColumn($table, 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
    ));

$table = $installer->getTable('sales/creditmemo');

$installer->getConnection()
    ->addColumn($table, 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
    ));

$installer->getConnection()
    ->addColumn($table, 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
    ));

if(!$installer->getConnection()->isTableExists($installer->getTable('pagarme_split_rules'))) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('pagarme_split_rules'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
                ), 'Id')
        ->addColumn('split_rule_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
                ), 'Pagarme Split Rule Id')
        ->addColumn('recipient_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
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
            'nullable' => true,
                ), 'Percentage that the recipient will receive the transaction amount')
        ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
            'nullable' => true,
                ), 'Value that the recipient will receive the transaction.')
        ->addColumn('shipping_charge', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 1, array(
            'nullable' => false,
            'default' => 0,
                ), 'Value that the recipient will receive the transaction.')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
                ), 'date time created row')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
                ), 'date time updated row');

    $installer->getConnection()->createTable($table);
}


if(!$installer->getConnection()->isTableExists($installer->getTable('pagarme_marketplace_menu'))) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('pagarme_marketplace_menu'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
                ), 'Id')
        ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
                ), 'Product Sku')
        ->addColumn('recipient_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
                ), 'Recipient id.')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
                ), 'date time created row')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
            'nullable' => false,
                ), 'date time updated row');

    $installer->getConnection()->createTable($table);
}

$entities = array(
    'quote_item',
    'order_item'
);

foreach ($entities as $entity) {
    $installer->addAttribute($entity, 'recipient_id', array(
        'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
        'visible' => true,
        'required' => false
    ));
}

$table = $installer->getTable('sales/quote_address');

$installer->getConnection()
    ->changeColumn($table, 'fee_amount', 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'base_fee_amount', 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$table = $installer->getTable('sales/order');

$installer->getConnection()
    ->changeColumn($table, 'fee_amount', 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'base_fee_amount', 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'fee_amount_invoiced', 'fee_amount_invoiced', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount Invoiced',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'base_fee_amount_invoiced', 'base_fee_amount_invoiced', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount Invoiced',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'fee_amount_refunded', 'fee_amount_refunded', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount Refunded',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'base_fee_amount_refunded', 'base_fee_amount_refunded', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount Refunded',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$table = $installer->getTable('sales/invoice');

$installer->getConnection()
    ->changeColumn($table, 'fee_amount', 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'base_fee_amount', 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$table = $installer->getTable('sales/creditmemo');

$installer->getConnection()
    ->changeColumn($table, 'fee_amount', 'fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

$installer->getConnection()
    ->changeColumn($table, 'base_fee_amount', 'base_fee_amount', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'unsigned' => true,
        'nullable' => false,
        'comment' => 'Base Fee Amount',
        'SCALE' => 4,
        'PRECISION' => 12,
    ));

if(!$installer->getConnection()->isTableExists($installer->getTable('pagarme_split_rules_group'))) {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('pagarme_split_rules_group'))
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'nullable' => false,
            'unsigned' => true,
            'primary' => true
                ), 'Id')
        ->addColumn('group_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false
                ), 'Group Name')
        ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, ARRAY(
            'nullable' => false
                ), 'Website ID');

    $installer->getConnection()->createTable($table);
}

$table = $installer->getTable('pagarme_split_rules');

$installer->getConnection()
    ->addColumn($table, 'group_id', array(
        'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'unsigned' => true,
        'nullable' => true,
        'comment'  => 'Group ID'
    ));

$installer->endSetup();
