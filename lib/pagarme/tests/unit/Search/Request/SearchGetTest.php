<?php

namespace PagarMe\SdkTest\Search\Request;

use PagarMe\Sdk\Search\Request\SearchGet;
use PagarMe\Sdk\RequestInterface;

class SearchGetTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'search';
    const TYPE   = 'transaction';

    /**
     * @test
     */
    public function mustContentBeCorrect()
    {
        $searchGet = new SearchGet(self::TYPE, $this->getQueryParams());

        $this->assertEquals(
            [
                'type'  => self::TYPE,
                'query' => $this->getQueryParams()
            ],
            $searchGet->getPayload()
        );
        $this->assertEquals(self::PATH, $searchGet->getPath());
        $this->assertEquals(RequestInterface::HTTP_GET, $searchGet->getMethod());
    }

    private function getQueryParams()
    {
        return [
            'query' => [
                'filtered' => [
                    'query' => ['match_all' => []],
                    'filter' => [
                    'and' => [
                        [
                            'range' => [
                                'date_created' => [
                                    'lte' => '2016-01-31',
                                    'gte' => '2016-01-01']
                                ]
                            ],
                            [
                                'or' => [
                                    [
                                        'term' => ['status' => 'waiting_payment']
                                    ],
                                    [
                                        'term' => ['status' => 'paid']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'sort' => [
                [
                    'date_created' => ['order' => 'desc']
                ]
            ],
            'size' => 5,
            'from' => 0
        ];
    }
}
