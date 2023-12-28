<?php

declare(strict_types=1);

namespace ForexAPI\Client;

class Client
{
    public const BASE_URI = 'https://beta.forexapi.pl/api/';
    public const ENDPOINT_LIVE = 'forex/live';
    public const ENDPOINT_CONVERT = 'forex/convert';

    private string $apiKey;
    private string $baseUri;
    private ?HttpAdapter $httpClient;

    public function __construct(string $apiKey, string $baseUri = self::BASE_URI, HttpAdapter $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->baseUri = $baseUri;
        $this->httpClient = $httpClient ?? new PsrHttpAdapter();
    }

    /**
     * @return array<LiveQuote>
     */
    public function getLiveQuotes(string $baseCurrency, array $counterCurrencies, int $precision = 4): array
    {
        $data = $this->get(self::ENDPOINT_LIVE, [
            'base' => $baseCurrency,
            'counter' => implode(',', $counterCurrencies),
            'precision' => $precision,
        ]);

        $quotes = [];

        foreach ($data['quotes'] as $quote) {
            $quotes[] = new LiveQuote(
                $quote['base'],
                $quote['counter'],
                $quote['bid'],
                $quote['ask'],
                $quote['mid'],
                $quote['timestamp'],
                $data['cost'],
            );
        }

        return $quotes;
    }

    public function getCurrencyRates(string $baseCurrency, array $counterCurrencies): array
    {
    }

    /**
     * @return array<ConversionResult>
     */
    public function convert(string $baseCurrency, array $counterCurrencies, float $amount): array
    {
        $data = $this->get(self::ENDPOINT_CONVERT, [
            'from' => $baseCurrency,
            'to' => implode(',', $counterCurrencies),
            'amount' => $amount,
        ]);

        $results = [];
        foreach ($data['results'] as $to => $result) {
            $results[] = new ConversionResult(
                $result['from'],
                $to,
                $data['amount'],
                $result,
                $data['timestamp'],
            );
        }

        return $results;
    }

    public function convertMultiple(string $baseCurrency, array $counterCurrencies, float $amount): array
    {
    }

    private function get(string $endpoint, array $query): array
    {
        return $this->httpClient->get($this->buildUrl($endpoint, $query), $this->apiKey);
    }

    private function buildUrl(string $endpoint, array $query): string
    {
        return trim($this->baseUri, '/').'/'.$endpoint.'?'.http_build_query($query);
    }
}
