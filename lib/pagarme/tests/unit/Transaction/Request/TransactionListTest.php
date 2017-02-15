<?php

namespace PagarMe\SdkTest\Transaction\Request;

use PagarMe\Sdk\Transaction\Request\TransactionList;
use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\RequestInterface;

class TransactionListTest extends \PHPUnit_Framework_TestCase
{
    const PATH = 'transactions';

    public function paginationProvider()
    {
        return [
            [null, null],
            [2, null],
            [1, 5],
            [4, 10],
            [7, 20],
        ];
    }

    /**
     * @dataProvider paginationProvider
     * @test
     */
    public function mustPayloadBeCorrect($page, $items)
    {
        $transactionCreate = new TransactionList($page, $items);

        $this->assertEquals(
            [
                'page'  => $page,
                'count' => $items
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @dataProvider paginationProvider
     * @test
     */
    public function mustPathBeCorrect($page, $items)
    {
        $transactionCreate = new TransactionList($page, $items);

        $this->assertEquals(self::PATH, $transactionCreate->getPath());
    }

    /**
     * @dataProvider paginationProvider
     * @test
     */
    public function mustMethodBeCorrect($page, $items)
    {
        $transactionCreate = new TransactionList($page, $items);

        $this->assertEquals(RequestInterface::HTTP_GET, $transactionCreate->getMethod());
    }
}
