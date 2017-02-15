<?php

namespace PagarMe\SdkTest\Transaction\Request;

use PagarMe\Sdk\Transaction\Request\CreditCardTransactionCreate;
use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\SplitRule\SplitRule;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\RequestInterface;

class CreditCardTransactionCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'transactions';

    const CARD_ID   = 1;
    const CARD_HASH = 'FC1mH7XLFU5fjPAzDsP0ogeAQh3qXRpHzkIrgDz64lITBUGwio67zm';

    public function installmentsProvider()
    {
        return [
            [1,true,null],
            [3,true, 'example.com'],
            [12,false, 'example.com'],
            [rand(1, 12), false, null]
        ];
    }

    /**
     * @dataProvider installmentsProvider
     * @test
     */
    public function mustPayloadBeCorrect($installments, $capture, $postbackUrl)
    {
        $transaction =  $this->getTransaction($installments, $capture, $postbackUrl);
        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => $installments,
                'payment_method' => 'credit_card',
                'capture'        => $capture,
                'postback_url'   => $postbackUrl,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'born_at'         => '15071991',
                    'document_number' => '10586649727',
                    'email'           => 'eduardo@eduardo.com',
                    'sex'             => 'M',
                    'address' => [
                        'street'        => 'rua teste',
                        'street_number' => 42,
                        'neighborhood'  => 'centro',
                        'zipcode'       => '01227200',
                        'complementary' => null
                    ],
                    'phone' => [
                        'ddd'    => 15,
                        'number' => 987523421
                    ]
                ],
                'metadata' => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainMonetarySplitRules()
    {
        $customerMock = $this->getCustomerMock();
        $cardMock     = $this->getCardMock();

        $rules = new SplitRuleCollection();
        $rules[]= new SplitRule([
            'amount'                => 100,
            'recipient'             => new Recipient(['id' => 1]),
            'liable'                => true,
            'charge_processing_fee' => true
        ]);
        $rules[]= new SplitRule([
            'amount'                => 1237,
            'recipient'             => new Recipient(['id' => 3]),
            'liable'                => false,
            'charge_processing_fee' => false
        ]);

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => 1,
                'capture'      => false,
                'postback_url' => null,
                'split_rules'  => $rules
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => 1,
                'payment_method' => 'credit_card',
                'capture'        => false,
                'postback_url'   => null,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'born_at'         => '15071991',
                    'document_number' => '10586649727',
                    'email'           => 'eduardo@eduardo.com',
                    'sex'             => 'M',
                    'address' => [
                        'street'        => 'rua teste',
                        'street_number' => 42,
                        'neighborhood'  => 'centro',
                        'zipcode'       => '01227200',
                        'complementary' => null
                    ],
                    'phone' => [
                        'ddd'    => 15,
                        'number' => 987523421
                    ]
                ],
                'split_rules' => [
                    0 => [
                        'amount'                => 100,
                        'recipient_id'          => 1,
                        'liable'                => true,
                        'charge_processing_fee' => true
                    ],
                    1 => [
                        'amount'                => 1237,
                        'recipient_id'          => 3,
                        'liable'                => false,
                        'charge_processing_fee' => false
                    ]
                ],
                'metadata' => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainPercentageSplitRules()
    {
        $customerMock = $this->getCustomerMock();
        $cardMock     = $this->getCardMock();

        $rules = new SplitRuleCollection();
        $rules[]= new SplitRule([
            'percentage'            => 90,
            'recipient'             => new Recipient(['id' => 1]),
            'liable'                => true,
            'charge_processing_fee' => true
        ]);
        $rules[]= new SplitRule([
            'percentage'            => 10,
            'recipient'             => new Recipient(['id' => 3]),
            'liable'                => false,
            'charge_processing_fee' => false
        ]);

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => 1,
                'capture'      => false,
                'postback_url' => null,
                'split_rules'  => $rules
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => 1,
                'payment_method' => 'credit_card',
                'capture'        => false,
                'postback_url'   => null,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'born_at'         => '15071991',
                    'document_number' => '10586649727',
                    'email'           => 'eduardo@eduardo.com',
                    'sex'             => 'M',
                    'address' => [
                        'street'        => 'rua teste',
                        'street_number' => 42,
                        'neighborhood'  => 'centro',
                        'zipcode'       => '01227200',
                        'complementary' => null
                    ],
                    'phone' => [
                        'ddd'    => 15,
                        'number' => 987523421
                    ]
                ],
                'split_rules' => [
                    0 => [
                        'percentage'            => 90,
                        'recipient_id'          => 1,
                        'liable'                => true,
                        'charge_processing_fee' => true
                    ],
                    1 => [
                        'percentage'            => 10,
                        'recipient_id'          => 3,
                        'liable'                => false,
                        'charge_processing_fee' => false
                    ]
                ],
                'metadata' => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainCardCvv()
    {
        $customerMock = $this->getCustomerMock();
        $cardMock     = $this->getCardMock();

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => 1,
                'capture'      => false,
                'postback_url' => null,
                'card_cvv'     => 123
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $payload = $transactionCreate->getPayload();

        $this->assertArrayHasKey('card_cvv', $payload);
        $this->assertEquals(123, $payload['card_cvv']);
    }

    /**
     * @dataProvider installmentsProvider
     * @test
     */
    public function mustPayloadContainCardHash($installments, $capture, $postbackUrl)
    {
        $customerMock = $this->getCustomerMock();
        $cardMock = $this->getMockBuilder('PagarMe\Sdk\Card\Card')
            ->disableOriginalConstructor()
            ->getMock();

        $cardMock->method('getHash')
            ->willReturn(self::CARD_HASH);

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => $installments,
                'capture'      => $capture,
                'postbackUrl'  => $postbackUrl
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_hash'      => self::CARD_HASH,
                'installments'   => $installments,
                'payment_method' => 'credit_card',
                'capture'        => $capture,
                'postback_url'   => $postbackUrl,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'born_at'         => '15071991',
                    'document_number' => '10586649727',
                    'email'           => 'eduardo@eduardo.com',
                    'sex'             => 'M',
                    'address' => [
                        'street'        => 'rua teste',
                        'street_number' => 42,
                        'neighborhood'  => 'centro',
                        'zipcode'       => '01227200',
                        'complementary' => null
                    ],
                    'phone' => [
                        'ddd'    => 15,
                        'number' => 987523421
                    ]
                ],
                'metadata' => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $transaction =  $this->getTransaction(rand(1, 12), false, null);
        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(self::PATH, $transactionCreate->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $transaction =  $this->getTransaction(rand(1, 12), false, null);
        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(RequestInterface::HTTP_POST, $transactionCreate->getMethod());
    }

    private function getTransaction($installments, $capture, $postbackUrl)
    {
        $customerMock = $this->getCustomerMock();
        $cardMock     = $this->getCardMock();

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => $installments,
                'capture'      => $capture,
                'postbackUrl'  => $postbackUrl
            ]
        );

        return $transaction;
    }

    public function getCardMock()
    {
        $cardMock = $this->getMockBuilder('PagarMe\Sdk\Card\Card')
            ->disableOriginalConstructor()
            ->getMock();

        $cardMock->method('getId')
            ->willReturn(self::CARD_ID);

        return $cardMock;
    }


    public function getCustomerMock()
    {
        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock->method('getBornAt')->willReturn('15071991');
        $customerMock->method('getDocumentNumber')->willReturn('10586649727');
        $customerMock->method('getEmail')->willReturn('eduardo@eduardo.com');
        $customerMock->method('getGender')->willReturn('M');
        $customerMock->method('getName')->willReturn('Eduardo Nascimento');
        $customerMock->method('getAddress')->willReturn(
            [
                'street'        => 'rua teste',
                'street_number' => 42,
                'neighborhood'  => 'centro',
                'zipcode'       => '01227200'
            ]
        );
        $customerMock->method('getPhone')->willReturn(
            [
                'ddd'    => 15,
                'number' => 987523421
            ]
        );

        return $customerMock;
    }
}
