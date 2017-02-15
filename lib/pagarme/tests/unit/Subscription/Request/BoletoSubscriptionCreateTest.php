<?php

namespace PagarMe\SdkTest\Subscription\Request;

use PagarMe\Sdk\Subscription\Request\BoletoSubscriptionCreate;
use PagarMe\Sdk\RequestInterface;

class BoletoSubscriptionCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'subscriptions';

    const PLAN_ID             = 123;
    const PLAN_PAYMENT_METHOD = 'boleto';

    const POSTBACK_URL   = 'http://myhost.com/postback';

    const CUSTOMER_NAME           = 'John Doe';
    const CUSTOMER_EMAIL          = 'john@test.com';
    const CUSTOMER_DOCUMENTNUMBER = '576981209';
    const CUSTOMER_BORN_AT        = '12031990';
    const CUSTOMER_GENDER         = 'm';

    const PHONE_DDD    = '11';
    const PHONE_NUMBER = '44445555';

    const ADDRESS_STREET       = 'Rua teste';
    const ADDRESS_STREETNUMBER = '123';
    const ADDRESS_NEIGHBORHOOD = 'Centro';
    const ADDRESS_ZIPCODE      = '01034020';
    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();
        $planMock->method('getId')->willReturn(self::PLAN_ID);


        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $phoneMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Phone')
            ->disableOriginalConstructor()
            ->getMock();

        $phoneMock->method('getDdd')->willReturn(self::PHONE_DDD);
        $phoneMock->method('getNumber')->willReturn(self::PHONE_NUMBER);

        $addressMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Address')
            ->disableOriginalConstructor()
            ->getMock();

        $addressMock->method('getStreet')
            ->willReturn(self::ADDRESS_STREET);
        $addressMock->method('getStreetNumber')
            ->willReturn(self::ADDRESS_STREETNUMBER);
        $addressMock->method('getNeighborhood')
            ->willReturn(self::ADDRESS_NEIGHBORHOOD);
        $addressMock->method('getZipcode')
            ->willReturn(self::ADDRESS_ZIPCODE);

        $customerMock->method('getName')
            ->willReturn(self::CUSTOMER_NAME);
        $customerMock->method('getEmail')
            ->willReturn(self::CUSTOMER_EMAIL);
        $customerMock->method('getDocumentNumber')
            ->willReturn(self::CUSTOMER_DOCUMENTNUMBER);
        $customerMock->method('getBornAt')
            ->willReturn(self::CUSTOMER_BORN_AT);
        $customerMock->method('getGender')
            ->willReturn(self::CUSTOMER_GENDER);
        $customerMock->method('getAddress')
            ->willReturn($addressMock);
        $customerMock->method('getPhone')
            ->willReturn($phoneMock);

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            self::POSTBACK_URL,
            $this->planMetadata(),
            []
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getPayload(),
            [
                'plan_id'        => self::PLAN_ID,
                'payment_method' => self::PLAN_PAYMENT_METHOD,
                'metadata'       => $this->planMetadata(),
                'customer'       => [
                    'name'            => self::CUSTOMER_NAME,
                    'email'           => self::CUSTOMER_EMAIL,
                    'document_number' => self::CUSTOMER_DOCUMENTNUMBER,
                    'address'         => [
                        'street'        => self::ADDRESS_STREET,
                        'street_number' => self::ADDRESS_STREETNUMBER,
                        'neighborhood'  => self::ADDRESS_NEIGHBORHOOD,
                        'zipcode'       => self::ADDRESS_ZIPCODE
                    ],
                    'phone'           => [
                        'ddd'    => self::PHONE_DDD,
                        'number' => self::PHONE_NUMBER
                    ],
                    'born_at'         => self::CUSTOMER_BORN_AT,
                    'gender'          => self::CUSTOMER_GENDER
                ]
            ]
        );
    }

    private function planMetadata()
    {
        return [
            'foo' => 'bar',
            'a'   => 'b'
        ];
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            null,
            [],
            []
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getMethod(),
            RequestInterface::HTTP_POST
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            null,
            [],
            []
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getPath(),
            self::PATH
        );
    }
}
