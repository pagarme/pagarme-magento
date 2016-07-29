<?php

if (!function_exists('curl_init')) {
	throw new Exception('PagarMe needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('PagarMe needs the JSON PHP extension.');
}


// function __autoload($class){
// 
// 	$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "Pagarme" . DIRECTORY_SEPARATOR;
// 	
// 	$file = $dir . ((strstr($class, "PagarMe_")) ? str_replace("PagarMe_", "", $class) : $class) . ".php";
// 
// 	if (file_exists($file)){
// 		require_once($file);
// 		return;
// 	}else{
// 		throw new Exception("Unable to load" .$class);
// 	}
// }


require(dirname(__FILE__) . '/lib/Pagarme/PagarMe.php');
require(dirname(__FILE__) . '/lib/Pagarme/Set.php');
require(dirname(__FILE__) . '/lib/Pagarme/Object.php');
require(dirname(__FILE__) . '/lib/Pagarme/Util.php');
require(dirname(__FILE__) . '/lib/Pagarme/Error.php');
require(dirname(__FILE__) . '/lib/Pagarme/Exception.php');
require(dirname(__FILE__) . '/lib/Pagarme/RestClient.php');
require(dirname(__FILE__) . '/lib/Pagarme/Request.php');
require(dirname(__FILE__) . '/lib/Pagarme/Model.php');
require(dirname(__FILE__) . '/lib/Pagarme/CardHashCommon.php');
require(dirname(__FILE__) . '/lib/Pagarme/TransactionCommon.php');
require(dirname(__FILE__) . '/lib/Pagarme/Transaction.php');
require(dirname(__FILE__) . '/lib/Pagarme/Plan.php');
require(dirname(__FILE__) . '/lib/Pagarme/Subscription.php');
require(dirname(__FILE__) . '/lib/Pagarme/Address.php');
require(dirname(__FILE__) . '/lib/Pagarme/Phone.php');
require(dirname(__FILE__) . '/lib/Pagarme/Card.php');
require(dirname(__FILE__) . '/lib/Pagarme/Bank_Account.php');
require(dirname(__FILE__) . '/lib/Pagarme/Recipient.php');
require(dirname(__FILE__) . '/lib/Pagarme/Customer.php');
require(dirname(__FILE__) . '/lib/Pagarme/Payable.php');

?>
