<?php

namespace PagarMe\SdkTest\BankAccount;

class CardBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Card\CardBuilder;

    /**
     * @test
     */
    public function mustCreateCardCorrectly()
    {
        $payload = '{"object":"card","id":"card_ci6y37h16wrxsmzyi","date_created":"2015-03-06T21:21:25.000Z","date_updated":"2015-03-06T21:21:26.000Z","brand":"visa","holder_name":"API CUSTOMER","first_digits":"401872","last_digits":"8048","fingerprint":"Jl9oOIiDjAjR","customer":null,"valid":true}';

        $card = $this->buildCard(json_decode($payload));

        $this->assertInstanceOf('PagarMe\Sdk\Card\Card', $card);
        $this->assertInstanceOf('\DateTime', $card->getDateCreated());
        $this->assertInstanceOf('\DateTime', $card->getDateUpdated());
    }
}
