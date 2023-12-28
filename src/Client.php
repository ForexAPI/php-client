<?php

declare(strict_types=1);

namespace ForexAPI\Client;

/**
 * @internal Use factory or DI container instead
 */
class Client implements ForexAPIClient
{
    public const BASE_URI = 'https://beta.forexapi.pl/api/';
    public const ENDPOINT_LIVE = 'forex/live';
    public const ENDPOINT_CONVERT = 'forex/convert';
    public const ENDPOINT_MARKET_STATUS = 'forex/market-status';
    public const ENDPOINT_USAGE = 'usage';

    private string $apiKey;
    private string $baseUri;
    private ?HttpAdapter $httpClient;

    public function __construct(string $apiKey, string $baseUri = self::BASE_URI, HttpAdapter $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->baseUri = $baseUri;
        $this->httpClient = $httpClient ?? new PsrHttpAdapter();
    }

    public function getLiveQuote(string $baseCurrency, string $counterCurrency, int $precision = 4): LiveQuote
    {
        return $this->getLiveQuotes($baseCurrency, [$counterCurrency], $precision)[0];
    }

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
            );
        }

        return $quotes;
    }

    public function getExchangeRate(string $baseCurrency, string $counterCurrency): ExchangeRate
    {
        return $this->getExchangeRates($baseCurrency, [$counterCurrency])[0];
    }

    public function getExchangeRates(string $baseCurrency, array $counterCurrencies, int $precision = 4): array
    {
        $conversions = $this->convertMany($baseCurrency, $counterCurrencies, 1.0, $precision);

        $exchangeRates = [];
        foreach ($conversions as $conversion) {
            $exchangeRates[] = new ExchangeRate(
                $conversion->getFrom(),
                $conversion->getTo(),
                $conversion->getResult(),
                $conversion->getTimestamp(),
            );
        }

        return $exchangeRates;
    }

    public function convert(string $baseCurrency, string $counterCurrency, float $amount, int $precision = 4): ConversionResult
    {
        return $this->convertMany($baseCurrency, [$counterCurrency], $amount, $precision)[0];
    }

    public function convertMany(string $baseCurrency, array $counterCurrencies, float $amount, int $precision = 4): array
    {
        $data = $this->get(self::ENDPOINT_CONVERT, [
            'from' => $baseCurrency,
            'to' => implode(',', $counterCurrencies),
            'amount' => $amount,
            'precision' => $precision,
        ]);

        $results = [];
        foreach ($data['results'] as $counter => $result) {
            $results[] = new ConversionResult(
                $data['from'],
                $counter,
                $data['amount'],
                $result,
                $data['timestamp'],
            );
        }

        return $results;
    }

    public function getUsage(): UsageQuota
    {
        $data = $this->get(self::ENDPOINT_USAGE, []);

        return new UsageQuota(
            $data['plan'],
            $data['used'],
            $data['limit'],
            $data['remaining'],
        );
    }

    public function getForexMarketStatus(): ForexMarketStatus
    {
        $data = $this->get(self::ENDPOINT_MARKET_STATUS, []);

        return new ForexMarketStatus(
            $data['is_market_open'],
        );
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
