<?php

declare(strict_types=1);

namespace ForexAPI\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

/**
 * A builder class for the ForexAPIClient.
 */
class ForexAPIClientBuilder
{
    private string $baseUri;
    private ?HttpAdapter $httpAdapter = null;
    private ?string $apiKey = null;

    private ?ClientInterface $psr18Client = null;
    private ?RequestFactoryInterface $psr17Factory = null;

    public function __construct()
    {
        $this->baseUri = Client::BASE_URI;
        $this->httpAdapter = new PsrHttpAdapter();
    }

    public function withApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function withBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    public function withHttpAdapter(HttpAdapter $httpAdapter): self
    {
        $this->httpAdapter = $httpAdapter;

        return $this;
    }

    public function withPsr18Client(ClientInterface $client): self
    {
        $this->psr18Client = $client;

        return $this;
    }

    public function withPsr17RequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->psr17Factory = $requestFactory;

        return $this;
    }

    public function build(string $apiKey): ForexAPIClient
    {
        if ($this->psr18Client || $this->psr17Factory) {
            $this->httpAdapter = new PsrHttpAdapter(
                $this->psr18Client,
                $this->psr17Factory
            );
        }

        return new Client($apiKey, $this->baseUri, $this->httpAdapter);
    }
}
