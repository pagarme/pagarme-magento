<?php

namespace PagarMe\Sdk\BulkAnticipation\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Recipient\Recipient;

class BulkAnticipationCreate implements RequestInterface
{
    use \PagarMe\Sdk\MicrosecondsFormatter;

    /**
     * @var  Recipient
     */
    private $recipient;

    /**
     * @var  DateTime
     */
    private $paymentDate;

    /**
     * @var  string
     */
    private $timeframe;

    /**
     * @var  int
     */
    private $requestedAmount;

    /**
     * @var  boolean
     */
    private $building;

    /**
     * @param  Recipient $recipient
     * @param  \DateTime $paymentDate
     * @param  string $timeframe
     * @param  int $requestedAmount
     * @param  boolean $building
     */
    public function __construct(
        Recipient $recipient,
        \DateTime $paymentDate,
        $timeframe,
        $requestedAmount,
        $building
    ) {
        $this->recipient       = $recipient;
        $this->paymentDate     = $paymentDate;
        $this->timeframe       = $timeframe;
        $this->requestedAmount = $requestedAmount;
        $this->building        = $building;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'payment_date'     => $this->getDateInMicroseconds(
                $this->paymentDate
            ),
            'timeframe'        => $this->timeframe,
            'requested_amount' => $this->requestedAmount,
            'building'         => $this->building
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf(
            'recipients/%s/bulk_anticipations',
            $this->recipient->getId()
        );
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
