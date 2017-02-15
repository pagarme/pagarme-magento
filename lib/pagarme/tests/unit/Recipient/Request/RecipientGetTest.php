<?php

namespace PagarMe\SdkTests\Recipient;

use PagarMe\Sdk\Recipient\Request\RecipientGet;
use PagarMe\Sdk\RequestInterface;

class RecipientGetTest extends \PHPUnit_Framework_TestCase
{
    const ID   = 're_x1y2z3';
    const PATH = 'recipients/re_x1y2z3';

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $recipientGet = new RecipientGet(self::ID);

        $this->assertEquals(self::PATH, $recipientGet->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $recipientGet = new RecipientGet(self::ID);

        $this->assertEquals(RequestInterface::HTTP_GET, $recipientGet->getMethod());
    }

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $recipientGet = new RecipientGet(self::ID);

        $this->assertEquals(
            [],
            $recipientGet->getPayload()
        );
    }
}
