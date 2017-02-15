<?php

namespace PagarMe\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use PagarMe\Sdk\PagarMe;

require_once __DIR__ . '../../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

abstract class BasicContext implements Context, SnippetAcceptingContext
{
    protected static $pagarMe;

    protected static function getPagarMe()
    {
        if (static::$pagarMe === null) {
            $companyData = self::createCompany();
            echo sprintf("Key: %s\n", $companyData->api_key->test);
            self::$pagarMe = new PagarMe(
                $companyData->api_key->test
            );
        }

        return self::$pagarMe;
    }

    private static function createCompany()
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
}
