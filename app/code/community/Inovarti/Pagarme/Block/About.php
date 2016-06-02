<?php
/*
 * @copyright   Copyright (C) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author     Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Inovarti_Pagarme_Block_About
extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

const MODULE_NAME = 'Inovarti_Pagarme';

function render (Varien_Data_Form_Element_Abstract $element)
{

$result = <<< RESULT
<br/>
<p style="font-size:23px; font-weight:bold;">
{$this->_getVersion()}
</p>
<br/>
RESULT;

return $result;

}

function _getVersion ()
{
    $version = Mage::getConfig ()->getModuleConfig (self::MODULE_NAME)->version;

    return sprintf ('%s %s - %s %s', $this->__('Module'), self::MODULE_NAME, $this->__('Version'), $version);
}

}

