<?php

declare(strict_types=1);

namespace ForexAPI\Client;

interface ForexAPIClient
{
    /**
     * Get live quote for given base currency and counter currency.
     * This data includes bid, ask and mid-prices.
     */
    public function getLiveQuote(string $baseCurrency, string $counterCurrency, int $precision = 4): LiveQuote;

    /**
     * Get live quotes for given base currency and counter currencies.
     * This data includes bid, ask and mid-prices.
     *
     * @return array<LiveQuote>
     */
    public function getLiveQuotes(string $baseCurrency, array $counterCurrencies, int $precision = 4): array;

    /**
     * Get single currency rate for given base currency and counter currency.
     * This data consists of a mid-price only.
     */
    public function getExchangeRate(string $baseCurrency, string $counterCurrency): ExchangeRate;

    /**
     * Get multiple currency rates at once for given base currency and counter currencies.
     * This data consists of a mid-price only.
     *
     * @return array<ExchangeRate>
     */
    public function getExchangeRates(string $baseCurrency, array $counterCurrencies): array;

    /**
     * Convert given amount from base currency to counter currency and return the result.
     * The API will use the half-up rounding method.
     */
    public function convert(string $baseCurrency, string $counterCurrency, float $amount, int $precision = 4): ConversionResult;

    /**
     * Convert given amount from base currency to multiple counter currencies and return the results.
     * The API will use the half-up rounding method.
     *
     * @return array{string, float}
     */
    public function convertMany(string $baseCurrency, array $counterCurrencies, float $amount, int $precision = 4): array;

    /**
     * Return the current status of the forex market.
     */
    public function getForexMarketStatus(): ForexMarketStatus;

    /**
     * Get usage statistics for the current API key.
     */
    public function getUsage(): UsageQuota;
}
