<?php

namespace PagarMe\SdkTest;

class CaseConverterTest extends \PHPUnit_Framework_TestCase
{
    public function upperCamelProvider()
    {
        return [
            ['some_string', 'SomeString'],
            ['string', 'String'],
            ['another_string', 'AnotherString'],
            ['string_with_Upper', 'StringWithUpper'],
            ['some_ABC_string', 'SomeABCString'],
        ];
    }

    /**
     * @dataProvider upperCamelProvider
     * @test
     */
    public function mustConvertToUpperCamelCase($snakeCase, $upperCamelCase)
    {
        $converter = $mock = $this->getMockForTrait('PagarMe\Sdk\CaseConverter');

        $this->assertEquals(
            $upperCamelCase,
            $converter->snakeToUpperCamel($snakeCase)
        );
    }

    public function lowerCamelProvider()
    {
        return [
            ['some_string', 'someString'],
            ['string', 'string'],
            ['another_string', 'AnotherString'],
            ['tring_with_Upper', 'stringWithUpper'],
            ['some_ABC_string', 'someABCString'],
        ];
    }

    /**
     * @dataProvider lowerCamelProvider
     * @test
     */
    public function mustConvertToLowerCamelCase()
    {
        $converter = $mock = $this->getMockForTrait('PagarMe\Sdk\CaseConverter');

        $this->assertEquals(
            'someString',
            $converter->snakeToLowerCamel('some_string')
        );
    }
}
