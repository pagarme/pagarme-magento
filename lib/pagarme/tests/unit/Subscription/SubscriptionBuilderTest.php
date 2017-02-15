<?php

namespace PagarMe\SdkTest\Subscription;

class SubscriptionGetTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Subscription\SubscriptionBuilder;
    /**
     * @test
     */
    public function mustSubscriptionBeCreatedCorrectly()
    {
        $payload = '{"object":"subscription","plan":{"object":"plan","id":94074,"amount":555,"days":30,"name":"Test Plan","trial_days":0,"date_created":"2016-12-28T19:38:22.203Z","payment_methods":["boleto","credit_card"],"charges":null,"installments":1},"id":155184,"current_transaction":{"object":"transaction","status":"paid","refuse_reason":null,"status_reason":"acquirer","acquirer_response_code":"0000","acquirer_name":"pagarme","acquirer_id":"57ab081b0450e4bd51d52af8","authorization_code":"292023","soft_descriptor":null,"tid":994237,"nsu":994237,"date_created":"2016-12-28T19:38:22.426Z","date_updated":"2016-12-28T19:38:22.838Z","amount":555,"authorized_amount":555,"paid_amount":555,"refunded_amount":0,"installments":1,"id":994237,"cost":50,"card_holder_name":"John Doe","card_last_digits":"6367","card_first_digits":"453970","card_brand":"visa","card_pin_mode":null,"postback_url":null,"payment_method":"credit_card","capture_method":"ecommerce","antifraud_score":null,"boleto_url":null,"boleto_barcode":null,"boleto_expiration_date":null,"referer":"api_key","ip":"104.154.133.31","subscription_id":155184,"split_rules":null,"metadata":{},"antifraud_metadata":{}},"postback_url":null,"payment_method":"credit_card","card_brand":"visa","card_last_digits":"6367","current_period_start":"2016-12-28T19:38:22.419Z","current_period_end":"2017-01-27T19:38:22.419Z","charges":0,"status":"paid","date_created":"2016-12-28T19:38:22.827Z","phone":{"object":"phone","ddi":"55","ddd":"11","number":"44445555","id":65836},"address":{"object":"address","street":"Rua Teste","complementary":null,"street_number":"123","neighborhood":"Centro","city":null,"state":null,"zipcode":"01034020","country":null,"id":68128},"customer":{"object":"customer","document_number":"25123317171","document_type":"cpf","name":"John Doe","email":"john@test.com","born_at":null,"gender":null,"date_created":"2016-12-28T19:38:22.090Z","id":122436},"card":{"object":"card","id":"card_citlycco400awdn6et5rzwgvp","date_created":"2016-09-27T20:45:14.406Z","date_updated":"2016-09-27T20:45:14.572Z","brand":"visa","holder_name":"John Doe","first_digits":"453970","last_digits":"6367","country":"SE","fingerprint":"Pw1WRMSxfu27","valid":true,"expiration_date":"0725"},"metadata":null}';

        $subscription = $this->buildSubscription(json_decode($payload));

        $this->assertInstanceOf(
            'PagarMe\Sdk\Subscription\Subscription',
            $subscription
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\Card\Card',
            $subscription->getCard()
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\Plan\Plan',
            $subscription->getPlan()
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\Customer\Customer',
            $subscription->getCustomer()
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\Transaction\AbstractTransaction',
            $subscription->getCurrentTransaction()
        );

        $this->assertInstanceOf(
            '\DateTime',
            $subscription->getCurrentPeriodStart()
        );

        $this->assertInstanceOf(
            '\DateTime',
            $subscription->getCurrentPeriodEnd()
        );
    }
}
