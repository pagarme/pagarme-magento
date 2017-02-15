<?php

namespace PagarMe\SdkTests\Customer;

class CustomerBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Customer\CustomerBuilder;

    /**
     * @test
     */
    public function mustCreateCustomerCorrectly()
    {
        $payload = '{"object":"customer","document_number":"25123317171","document_type":"cpf","name":"John Doe","email":"john@test.com","born_at":null,"gender":null,"date_created":"2016-12-28T19:38:28.618Z","id":122444,"addresses":[{"object":"address","street":"Rua Teste","complementary":null,"street_number":"123","neighborhood":"Centro","city":null,"state":null,"zipcode":"01034020","country":null,"id":68136}],"phones":[{"object":"phone","ddi":"55","ddd":"11","number":"44445555","id":65844}]}';

        $customer = $this->buildCustomer(json_decode($payload));

        $this->assertInstanceOf('PagarMe\Sdk\Customer\Customer', $customer);
        $this->assertInstanceOf('\DateTime', $customer->getDateCreated());
    }
}
