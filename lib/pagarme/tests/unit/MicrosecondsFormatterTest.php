<?php

namespace PagarMe\SdkTest;

class MicrosecondsFormatterTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\MicrosecondsFormatter;

    public function dateTimeProvider()
    {
        date_default_timezone_set('America/Sao_Paulo');

        return [
            [new \DateTime('2016-12-19'), '1482112800000'],
            [new \DateTime('2017-06-14'), '1497409200000'],
            [new \DateTime('2018-01-01'), '1514772000000'],
            [new \DateTime('2019-03-15'), '1552618800000'],
        ];
    }

    /**
     * @dataProvider dateTimeProvider
     * @test
     */
    public function mustFormatDateTimeToMicroseconds($dateTime, $formattedTime)
    {
        $this->assertEquals(
            $formattedTime,
            $this->getDateInMicroseconds($dateTime)
        );
    }
}
