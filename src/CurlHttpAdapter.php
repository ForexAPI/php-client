<?php

declare(strict_types=1);

namespace ForexAPI\Client;

use Http\Client\Curl\Client as CurlClient;
use Nyholm\Psr7\Factory\Psr17Factory;

class CurlHttpAdapter extends PsrHttpAdapter
{
    public function __construct()
    {
        $factory = new Psr17Factory();
        parent::__construct(new CurlClient($factory, $factory), $factory);
    }
}
