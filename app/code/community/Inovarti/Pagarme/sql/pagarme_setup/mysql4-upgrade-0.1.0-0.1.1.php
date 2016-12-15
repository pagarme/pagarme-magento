<?php
/*
 * @copyright   Copyright (C) 2015 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup('pagarme_setup');
$installer->startSetup();
$resource = Mage::getSingleton('core/resource')->getConnection('core_write');

function addFeeColumns ($installer, $resource)
{
    $table = $installer->getTable ('sales/quote_address');
    
    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'fee_amount';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Fee Amount',
            ));
    }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'base_fee_amount';");
    if ($hasColumn->rowCount() == 0) {

        $installer->getConnection ()
            ->addColumn ($table, 'base_fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Base Fee Amount',
            ));
    }

    $table = $installer->getTable ('sales/order');
    
    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'fee_amount';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Fee Amount',
            ));
    }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'base_fee_amount';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'base_fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Base Fee Amount',
            ));
        }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'fee_amount_invoiced';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'fee_amount_invoiced', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Fee Amount Invoiced',
            ));
    }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'base_fee_amount_invoiced';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'base_fee_amount_invoiced', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Base Fee Amount Invoiced',
            ));
    }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'fee_amount_refunded';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'fee_amount_refunded', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Fee Amount Refunded',
            ));
    }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'base_fee_amount_refunded';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'base_fee_amount_refunded', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Base Fee Amount Refunded',
            ));
    }
    $table = $installer->getTable ('sales/invoice');
    
    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'fee_amount';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Fee Amount',
            ));
    }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'base_fee_amount';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'base_fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Base Fee Amount',
            ));
    }
    $table = $installer->getTable ('sales/creditmemo');
    
    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'fee_amount';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Fee Amount',
            ));
    }

    $hasColumn = $resource->query("SHOW COLUMNS FROM {$table} WHERE field = 'base_fee_amount';");
    if ($hasColumn->rowCount() == 0) {
        $installer->getConnection ()
            ->addColumn ($table, 'base_fee_amount', array(
                'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Base Fee Amount',
            ));
    }
}

addFeeColumns ($installer, $resource);

$installer->endSetup();