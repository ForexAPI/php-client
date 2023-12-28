<?php

namespace ForexAPI\Client;

use ForexAPI\Client\Exception\ClientException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class PsrHttpAdapter implements HttpAdapter
{
    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;

    public function __construct(ClientInterface $client = null, RequestFactoryInterface $requestFactory = null)
    {
        $this->client = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
    }

    public function get(string $url, string $apiKey): array
    {
        $request = $this->requestFactory->createRequest('GET', $url);
        $request = $request
            ->withHeader('x-auth-token', $apiKey)
            ->withHeader('accept', 'application/json')
            ->withHeader('user-agent', ForexAPIClient::NAME)
        ;

        $response = $this->client->sendRequest($request);

        $raw = $response->getBody()->getContents();
        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

        if($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            throw new ClientException($data['error'] ?? 'Unknown client error');
        }

        return $data;
    }
}