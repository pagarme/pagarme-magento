<?php

namespace PagarMe\Sdk;

abstract class AbstractHandler
{
    /**
     * @var Client | Wrapper do Guzzle Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
