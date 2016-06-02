<?php
/*
 * @copyright   Copyright (C) 2015 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Sales_Model_Resource_Setup('pagarme_setup');
$installer->startSetup();

function addFeeColumns ($installer)
{
    $table = $installer->getTable ('sales/quote_address');
    
    $installer->getConnection ()
        ->addColumn ($table, 'fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Fee Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'base_fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Base Fee Amount',
        ));

    $table = $installer->getTable ('sales/order');
    
    $installer->getConnection ()
        ->addColumn ($table, 'fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Fee Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'base_fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Base Fee Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'fee_amount_invoiced', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Fee Amount Invoiced',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'base_fee_amount_invoiced', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Base Fee Amount Invoiced',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'fee_amount_refunded', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Fee Amount Refunded',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'base_fee_amount_refunded', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Base Fee Amount Refunded',
        ));

    $table = $installer->getTable ('sales/invoice');
    
    $installer->getConnection ()
        ->addColumn ($table, 'fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Fee Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'base_fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Base Fee Amount',
        ));

    $table = $installer->getTable ('sales/creditmemo');
    
    $installer->getConnection ()
        ->addColumn ($table, 'fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Fee Amount',
        ));
    $installer->getConnection ()
        ->addColumn ($table, 'base_fee_amount', array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'unsigned' => true,
            'nullable' => false,
            'comment' => 'Base Fee Amount',
        ));
}

addFeeColumns ($installer);

$installer->endSetup();

