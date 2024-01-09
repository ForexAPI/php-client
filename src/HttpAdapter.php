<?php

declare(strict_types=1);

namespace ForexAPI\Client;

use ForexAPI\Client\Exception\ClientException;
use ForexAPI\Client\Exception\ServerException;

interface HttpAdapter
{
    /**
     * @throws ClientException When API responds with a client type error
     * @throws ServerException When API responds with a server type error
     *
     * @phpstan-return array<mixed>
     */
    public function get(string $url, string $apiKey): array;
}
