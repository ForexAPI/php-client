<?php

declare(strict_types=1);

namespace ForexAPI\Client;

use ForexAPI\Client\Exception\ClientException;
use ForexAPI\Client\Exception\ServerException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface as PsrClientException;
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
            ->withHeader('user-agent', 'ForexAPI/PHP-Client')
        ;

        try {
            $response = $this->client->sendRequest($request);
        } catch (PsrClientException $e) {
            throw new ClientException(sprintf('Error while trying to send request: %s', $e->getMessage()), 0, $e);
        }

        $raw = (string) $response->getBody();

        try {
            $data = json_decode($raw, true, 512, \JSON_THROW_ON_ERROR);

            if (!is_array($data)) {
                throw new ServerException('Invalid JSON response');
            }
        } catch (JsonException $e) {
            throw new ServerException('Invalid JSON response', 0, $e);
        }

        if ($response->getStatusCode() >= 500) {
            throw new ServerException($this->getErrorMessage($data));
        }

        if ($response->getStatusCode() >= 400) {
            throw new ClientException($this->getErrorMessage($data));
        }

        return $data;
    }

    /**
     * @param array<mixed> $data
     */
    private function getErrorMessage(array $data): string
    {
        if (isset($data['error']) && is_string($data['error'])) {
            return $data['error'];
        }

        return 'Unknown error';
    }
}
