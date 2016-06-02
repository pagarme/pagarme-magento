<?php
/*
 * @copyright  Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer address model
 *
 * @category   Mage
 * @package    Mage_Customer
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Inovarti_Pagarme_Model_Customer_Address
extends Mage_Customer_Model_Address
// extends Mage_Customer_Model_Address_Abstract
{

/**
 * Perform basic validation
 *
 * @return void
 */
protected function _basicCheck()
{
    parent::_basicCheck();

    Mage::helper('pagarme')->_validateCustomerAddress($this);
}

}

