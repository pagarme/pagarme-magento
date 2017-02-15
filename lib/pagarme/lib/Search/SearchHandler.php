<?php

namespace PagarMe\Sdk\Search;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Search\Request\SearchGet;

class SearchHandler extends AbstractHandler
{
    /**
     * @param string $type
     * @param array $queryParams
     * @return \stdClass
     */
    public function get($type, $queryParams)
    {
        $request = new SearchGet($type, $queryParams);

        return $this->client->send($request);
    }
}
