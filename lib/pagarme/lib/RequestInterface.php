<?php

namespace PagarMe\Sdk;

interface RequestInterface
{
    const HTTP_GET    = 'GET';
    const HTTP_POST   = 'POST';
    const HTTP_PUT    = 'PUT';
    const HTTP_DELETE = 'DELETE';

    /**
     * @return array
     */
    public function getPayload();

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return string
     */
    public function getMethod();
}
