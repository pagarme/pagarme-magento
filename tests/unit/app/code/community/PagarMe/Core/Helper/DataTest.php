<?php


class PagarMe_Core_Helper_DataTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    public function setup()
    {
        $this->helper = Mage::helper('pagarme_core');
    }
    /**
     * @test
     */
    public function mustPrepareCustomerData()
    {
        $plainData = [
            'pagarme_modal_customer_document_number' => '251.233.171-71',
            'pagarme_modal_customer_document_type' => 'cpf',
            'pagarme_modal_customer_name' => 'John Doe',
            'pagarme_modal_customer_email' => 'john@test.com',
            'pagarme_modal_customer_born_at' => null,
            'pagarme_modal_customer_gender' => null,
            'pagarme_modal_customer_address_street_1' => 'Rua Teste',
            'pagarme_modal_customer_address_street_2' => '123',
            'pagarme_modal_customer_address_street_3' => null,
            'pagarme_modal_customer_address_street_4' => 'Centro',
            'pagarme_modal_customer_address_city' => null,
            'pagarme_modal_customer_address_state' => null,
            'pagarme_modal_customer_address_zipcode' => '01034020',
            'pagarme_modal_customer_address_country' => null,
            'pagarme_modal_customer_phone_ddd' => '11',
            'pagarme_modal_customer_phone_number' => '44445555'
        ];

        $customerData = $this->helper->prepareCustomerData($plainData);

        $customerTemplate = '{"document_number":"25123317171","document_type":"cpf","name":"John Doe","email":"john@test.com","born_at":null,"gender":null,"date_created": null, "addresses":[{"street":"Rua Teste","complementary":null,"street_number":"123","neighborhood":"Centro","city":null,"state":null,"zipcode":"01034020","country":null}],"phones":[{"ddd":"11","number":"44445555"}]}';

        $this->assertEquals(json_decode($customerTemplate), $customerData);
    }

    /**
     * @test
     */
    public function mustReturnDddFromPhoneNumber()
    {
        $expected = '15';
        $phone = '(15) 5485-5444';

        $this->assertEquals($expected, $this->helper->getDddFromPhoneNumber($phone));
    }

    /**
     * @test
     */
    public function mustReturnPhoneWithoutDdd()
    {
        $expected = '51115849';
        $phone = '11 5111-5849';

        $this->assertEquals($expected, $this->helper->getPhoneWithoutDdd($phone));
    }

    /**
     * @test
     *
     * @dataProvider getFloatValues
     */
    public function mustParseFloatValuesToInteger($value)
    {
        $subject = $this->helper->parseAmountToCents($value);

        $this->assertInternalType('int', $subject);
    }

    /**
     * @test
     *
     * @dataProvider getIntegerValues
     */
    public function mustParseIntegerValuesToToFloat($value)
    {
        $subject = $this->helper->parseAmountToCurrency($value);

        $this->assertInternalType('float', $subject);
    }

    /**
     * @test
     */
    public function mustConcatenateTheCustomerNameFromQuote()
    {
        $quote = Mage::getModel('sales/quote');
        $quote->setCustomerFirstname('José');
        $quote->setCustomerMiddlename('das');
        $quote->setCustomerLastname('Couves');

        $customerName = $this->helper->getCustomerNameFromQuote($quote);

        $this->assertEquals(
            'José das Couves',
            $customerName
        );
    }

    /**
     * @return array
     */
    public function getFloatValues()
    {
        return [
            [123.45],
            [1.1],
            [0.8],
            [12345678.90],
        ];
    }

    /**
     * @return array
     */
    public function getIntegerValues()
    {
        return [
            [12345],
            [2],
            [1],
            [1234567890],
        ];
    }
}
