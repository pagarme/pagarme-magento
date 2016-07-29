<?php

function authorizeFromEnv()
{
    $apiKey = getenv('PAGARME_API_KEY');
    if (!$apiKey)
        $apiKey = "ak_test_Rw4JR98FmYST2ngEHtMvVf5QJW7Eoo";
    PagarMe::setApiKey($apiKey);
}

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok) {
    $ok = @include_once(dirname(__FILE__).'/../vendor/simpletest/simpletest/autorun.php');
}

if (!$ok) {
    echo "MISSING DEPENDENCY: The PagarMe API test cases depend on SimpleTest. ".
         "Download it at <http://www.simpletest.org/>, and either install it ".
         "in your PHP include_path or put it in the test/ directory.\n";
    exit(1);
}

// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '../../Pagarme.php');
require_once(dirname(__FILE__) . '/PagarMe/TestCase.php');
require_once(dirname(__FILE__) . '/PagarMe/Object.php');
require_once(dirname(__FILE__) . '/PagarMe/Util.php');
require_once(dirname(__FILE__) . '/PagarMe/Set.php');
require_once(dirname(__FILE__) . '/PagarMe/Transaction.php');
require_once(dirname(__FILE__) . '/PagarMe/Plan.php');
require_once(dirname(__FILE__) . '/PagarMe/Subscription.php');
require_once(dirname(__FILE__) . '/PagarMe/Bank_Account.php');
require_once(dirname(__FILE__) . '/PagarMe/Recipient.php');
require_once(dirname(__FILE__) . '/PagarMe/Payable.php');
