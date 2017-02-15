<?php

namespace PagarMe\Sdk\Postback;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Transaction\AbstractTransaction;
use PagarMe\Sdk\Postback\Request\PostbackList;
use PagarMe\Sdk\Postback\Request\PostbackGet;

class PostbackHandler extends AbstractHandler
{
    use \PagarMe\Sdk\Postback\PostbackBuilder;

    /**
     * @param AbstractTransaction $transaction
     * @return array
     */
    public function getList(AbstractTransaction $transaction)
    {
        $request = new PostbackList($transaction);

        $response = $this->client->send($request);

        $postbacks = [];

        foreach ($response as $postbackData) {
            $postbacks[] = $this->buildPostback($postbackData);
        }

        return $postbacks;
    }

    /**
     * @param AbstractTransaction $transaction
     * @param string $postbackId
     * @return Postback
     */
    public function get(AbstractTransaction $transaction, $postbackId)
    {
        $request = new PostbackGet($transaction, $postbackId);

        $response = $this->client->send($request);

        return $this->buildPostback($response);
    }

    /**
     * @param AbstractTransaction $transaction
     * @param string $postbackId
     * @return Postback
     */
    public function redeliver(AbstractTransaction $transaction, $postbackId)
    {
        $request = new PostbackGet($transaction, $postbackId);

        $response = $this->client->send($request);

        return $this->buildPostback($response);
    }

    /**
     * @param string $payload
     * @param string $signature
     * @return boolean
     */
    public function validateRequest($payload, $signature)
    {
        $parts = explode("=", $signature, 2);

        if (count($parts) != 2) {
            return false;
        }

        $apiKey = $this->client->getApiKey();
        $algorithm = $parts[0];
        $hash = $parts[1];

        return hash_hmac($algorithm, $payload, $apiKey) === $hash;
    }
}
