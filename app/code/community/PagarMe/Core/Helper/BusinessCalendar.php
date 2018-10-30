<?php
class PagarMe_Core_Helper_BusinessCalendar
{
    /**
     * @param DateTime $date
     *
     * @return bool
     */
    public function isBusinessDay($date)
    {
        $weekDay = $date->format('w');
        $isWeekend = $weekDay == '0' || $weekDay == '6';

        return !$isWeekend && !$this->isHoliday($date);
    }

    /**
     * @param DateTime $date
     * @return bool
     */
    public function isHoliday($date)
    {
        $holidayCalendar = $this->getHolidaysCalendar($date);

        $isHoliday = array_filter(
            $holidayCalendar,
            function ($holiday) use ($date) {
                return $holiday['date'] ==
                    $date->format('Y-m-d');
            }
        );

        return (bool)$isHoliday;
    }

    /**
     * @param DateTime $date
     *
     * @return array
     */
    public function getHolidaysCalendar($date)
    {
        $holidaysSource = sprintf(
            '%s%s%s',
            'https://raw.githubusercontent.com/pagarme/',
            'business-calendar/master/src/brazil/',
            $this->getDateYear($date).'.json'
        );

        $holidayClient = new GuzzleHttp\Client(
            [
                'uri' => $holidaysSource
            ]
        );

        $holidaysFile = $holidayClient
            ->get($holidaysSource)
            ->getBody()
            ->getContents();

        $holidayJson = json_decode($holidaysFile, true);
        return $holidayJson['calendar'];
    }

    public function getDateYear($date)
    {
        return $date->format('Y');
    }
}
