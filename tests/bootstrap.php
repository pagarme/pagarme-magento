<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Mage.php';

spl_autoload_unregister(array(\Varien_Autoload::instance(), 'autoload'));

spl_autoload_register(function ($className) {
    $filePath = strtr(
        ltrim($className, '\\'),
        array(
            '\\' => '/',
            '_'  => '/'
        )
    );
    @include $filePath . '.php';
});

Mage::init();
Mage::app()
    ->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

Mage::getModel('cms/page')
    ->load(2)
    ->setData('content', '{{block type="catalog/product_list" name="home.catalog.product.list" alias="products_homepage" category_id="9" template="catalog/product/list.phtml"}}')
    ->save();

Mage::getModel('pagarme_core/observer_autoloader')
    ->registerSplAutoloader(new Varien_Event_Observer());

function getCompanyTemporary()
{
    $ch = curl_init();

    curl_setopt(
        $ch,
        CURLOPT_URL,
        "https://api.pagar.me/1/companies/temporary"
    );

    date_default_timezone_set('America/Sao_Paulo');

    $params = sprintf(
        'name=acceptance_test_company&email=%s@sdksuitetest.com&password=password',
        date(
            'YmdHis'
        )
    );

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        $params
    );

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $companyData = json_decode($result);

    curl_close($ch);

    return $companyData;
}

$apiKey = getenv('API_KEY');
$encriptionKey = getenv('ENCRYPTION_KEY');

if (!$apiKey && !$encriptionKey) {
    $companyData = getCompanyTemporary();

    $apiKey = $companyData->api_key->test;
    $encriptionKey = $companyData->encryption_key->test;
}

define('PAGARME_API_KEY', $apiKey);
define('PAGARME_ENCRYPTION_KEY', $encriptionKey);
