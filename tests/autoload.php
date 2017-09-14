<?php
$path = ini_get('include_path') . PATH_SEPARATOR;
$path.= dirname(__FILE__) . '/../app' . PATH_SEPARATOR;
$path.= dirname(__FILE__);

ini_set('include_path', $path);
ini_set('memory_limit', '512M');
require_once 'Mage.php';
Mage::app('default');
session_start();
