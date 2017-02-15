<?php

namespace PagarMe\Sdk\Card\Request;

use PagarMe\Sdk\RequestInterface;

class CardCreate implements RequestInterface
{
    /**
     * @var int
     */
    private $cardNumber;

    /**
     * @var string
     */
    private $holderName;

    /**
     * @var int
     */
    private $cardExpirationDate;

    /**
     * @param int $cardNumber
     * @param string $holderName
     * @param int $cardExpirationDate
     */
    public function __construct($cardNumber, $holderName, $cardExpirationDate)
    {
        $this->cardNumber         = $cardNumber;
        $this->holderName         = $holderName;
        $this->cardExpirationDate = $cardExpirationDate;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'card_number'          => $this->cardNumber,
            'holder_name'          => $this->holderName,
            'card_expiration_date' => $this->cardExpirationDate
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'cards';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
