<?php

namespace PagarMe\Sdk\Search\Request;

use PagarMe\Sdk\RequestInterface;

class SearchGet implements RequestInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $queryParams;

    /**
     * @param string $type
     * @param array $queryParams
     */
    public function __construct($type, $queryParams)
    {
        $this->type        = $type;
        $this->queryParams = $queryParams;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getPayload()
    {
        return [
            'type' => $this->type,
            'query' => $this->queryParams
        ];
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPath()
    {
        return 'search';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getMethod()
    {
        return self::HTTP_GET;
    }
}
