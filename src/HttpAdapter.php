<?php

namespace ForexAPI\Client;

use ForexAPI\Client\Exception\ClientException;
use ForexAPI\Client\Exception\ServerException;

interface HttpAdapter
{
    /**
     * @throws ClientException When API responds with a client type error
     * @throws ServerException When API responds with a server type error
     * @return array
     */
    public function get(string $url, string $apiKey): array;
}