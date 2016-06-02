<?php
/*
 * @copyright  Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Inovarti_Pagarme_Model_Sales_Quote_Address
extends Mage_Sales_Model_Quote_Address
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

