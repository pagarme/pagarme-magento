<?php

namespace PagarMe\SdkTest\Card\Request;

use PagarMe\Sdk\Card\Request\CardCreate;
use PagarMe\Sdk\RequestInterface;

class CardCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH            = 'cards';
    const CARD_NUMBER     = '4539401723324663';
    const CARD_HOLDER     = 'João Silva';
    const CARD_EXPIRATION = '0423';

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $cardCreate = new CardCreate(
            self::CARD_NUMBER,
            self::CARD_HOLDER,
            self::CARD_EXPIRATION
        );

        $this->assertEquals(
            [
                'card_number'          => self::CARD_NUMBER,
                'holder_name'          => self::CARD_HOLDER,
                'card_expiration_date' => self::CARD_EXPIRATION
            ],
            $cardCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $cardCreate = new CardCreate(
            self::CARD_NUMBER,
            self::CARD_HOLDER,
            self::CARD_EXPIRATION
        );

        $this->assertEquals(self::PATH, $cardCreate->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $cardCreate = new CardCreate(
            self::CARD_NUMBER,
            self::CARD_HOLDER,
            self::CARD_EXPIRATION
        );

        $this->assertEquals(RequestInterface::HTTP_POST, $cardCreate->getMethod());
    }
}
