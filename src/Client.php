<?php

declare(strict_types=1);

namespace ForexAPI\Client;

/**
 * @internal Use factory or DI container instead
 */
class Client implements ForexAPIClient
{
    public const string BASE_URI = 'https://api.forexapi.eu/v1';
    public const string ENDPOINT_LIVE = 'live';
    public const string ENDPOINT_CONVERT = 'convert';
    public const string ENDPOINT_MARKET_STATUS = 'market-status';
    public const string ENDPOINT_USAGE = 'usage';

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUri = self::BASE_URI,
        private readonly HttpAdapter $httpAdapter = new PsrHttpAdapter()
    ) {
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
                from: $data['from'],
                to: $counter,
                amount: $data['amount'],
                result: $result,
                timestamp: $data['timestamp'],
            );
        }

        return $results;
    }

    public function getUsage(): UsageQuota
    {
        $data = $this->get(self::ENDPOINT_USAGE, []);

        return new UsageQuota(
            plan: $data['plan'],
            used: $data['used'],
            limit: $data['limit'],
            remaining: $data['remaining'],
        );
    }

    public function getForexMarketStatus(): ForexMarketStatus
    {
        $data = $this->get(self::ENDPOINT_MARKET_STATUS, []);

        return new ForexMarketStatus(
            isOpen: $data['is_market_open'],
        );
    }

    /**
     * @param array<string, float|int|string> $query
     *
     * @return array<string, mixed>
     */
    private function get(string $endpoint, array $query): array
    {
        return $this->httpAdapter->get($this->buildUrl($endpoint, $query), $this->apiKey);
    }

    /**
     * @param array<string, float|int|string> $query
     */
    private function buildUrl(string $endpoint, array $query): string
    {
        return trim($this->baseUri, '/').'/'.$endpoint.'?'.http_build_query($query);
    }
}
