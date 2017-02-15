<?php

namespace PagarMe\Sdk;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @param \GuzzleHttp\Client $client
     * @param string $apiKey
     * @param int|null $timeout
     */
    public function __construct(GuzzleClient $client, $apiKey, $timeout = null)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;

        if (!is_null($timeout)) {
            $this->setDefaultTimeout($timeout);
        }
    }

    /**
     * @param int $timeout
     */
    public function setDefaultTimeout($timeout)
    {
        $this->client->setDefaultOption('timeout', $timeout);
    }

    /**
     * @return int
     */
    public function getDefaultTimeout()
    {
        return $this->client->getDefaultOption('timeout');
    }

    /**
     * @param RequestInterface $apiRequest
     * @return \stdClass
     * @throws ClientException
     */
    public function send(RequestInterface $apiRequest)
    {
        $request = $this->client->createRequest(
            $apiRequest->getMethod(),
            $apiRequest->getPath(),
            $this->buildBody($apiRequest)
        );

        try {
            $response = $this->client->send($request);
            return json_decode($response->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse()->getBody()->getContents();
            $code = $exception->getResponse()->getStatusCode();
            throw new ClientException($response, $code);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            throw new ClientException(
                $exception->getMessage(),
                $exception->getCode()
            );
        }
    }

    /**
     * @param RequestInterface $apiRequest
     * @return array
     */
    private function buildBody(RequestInterface $request)
    {
        return [
            'json' => array_merge(
                $request->getPayload(),
                [
                    'api_key' => $this->apiKey
                ]
            )
        ];
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
