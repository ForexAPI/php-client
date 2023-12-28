<?php

declare(strict_types=1);

namespace ForexAPI\Tests\Client;

use ForexAPI\Client\Client;
use ForexAPI\Client\PsrHttpAdapter;
use Http\Client\Common\PluginClient;
use Http\Client\Plugin\Vcr\NamingStrategy\PathNamingStrategy;
use Http\Client\Plugin\Vcr\Recorder\FilesystemRecorder;
use Http\Client\Plugin\Vcr\RecordPlugin;
use Http\Client\Plugin\Vcr\ReplayPlugin;
use Http\Discovery\Psr18ClientDiscovery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ForexAPI\Client\Client
 */
class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $namingStrategy = new PathNamingStrategy();
        $recorder = new FilesystemRecorder(__DIR__.'/recordings', null, [
            '#(CF-Cache-Status|CF-RAY|Report-To|NEL|alt-svc|x-debug-token|x-debug-token-link):.*\n#' => '',
        ]);

        $record = new RecordPlugin($namingStrategy, $recorder);
        $replay = new ReplayPlugin($namingStrategy, $recorder, false);
        $httpClient = new PluginClient(Psr18ClientDiscovery::find(), [$replay, $record]);

        // If you want to create recordings, you need to set your API key here. Remember to remove it before committing!
        $apikey = 'INSERT-YOUR-API-KEY-HERE';
        $adapter = new PsrHttpAdapter($httpClient);
        $this->client = new Client($apikey, Client::BASE_URI, $adapter);
    }

    public function test_get_live_quote(): void
    {
        $quote = $this->client->getLiveQuote('USD', 'PLN');

        $this->assertEquals('USD', $quote->getBase());
        $this->assertEquals('PLN', $quote->getCounter());
        $this->assertIsFloat($quote->getBid());
        $this->assertIsFloat($quote->getAsk());
        $this->assertIsFloat($quote->getMid());
        $this->assertIsInt($quote->getTimestamp());
    }

    public function test_get_forex_market_status(): void
    {
        $status = $this->client->getForexMarketStatus();
        $this->assertIsBool($status->isOpen());
    }

    public function test_get_usage(): void
    {
        $usage = $this->client->getUsage();
        $this->assertIsString($usage->getPlan());
        $this->assertIsInt($usage->getLimit());
        $this->assertIsInt($usage->getRemaining());
        $this->assertIsInt($usage->getUsed());
    }

    public function test_get_live_quotes(): void
    {
        $quotes = $this->client->getLiveQuotes('USD', ['EUR', 'GBP']);

        $this->assertCount(2, $quotes);
        foreach ($quotes as $quote) {
            $this->assertEquals('USD', $quote->getBase());
            $this->assertContains($quote->getCounter(), ['EUR', 'GBP']);
            $this->assertIsFloat($quote->getBid());
            $this->assertIsFloat($quote->getAsk());
            $this->assertIsFloat($quote->getMid());
            $this->assertIsInt($quote->getTimestamp());
        }
    }

    public function test_get_exchange_rate(): void
    {
        $exchangeRate = $this->client->getExchangeRate('USD', 'PLN');

        $this->assertEquals('USD', $exchangeRate->getFrom());
        $this->assertEquals('PLN', $exchangeRate->getTo());
        $this->assertIsFloat($exchangeRate->getRate());
        $this->assertIsInt($exchangeRate->getTimestamp());
    }

    public function test_get_exchange_rates(): void
    {
        $exchangeRates = $this->client->getExchangeRates('USD', ['EUR', 'GBP']);
        $this->assertCount(2, $exchangeRates);

        foreach ($exchangeRates as $exchangeRate) {
            $this->assertEquals('USD', $exchangeRate->getFrom());
            $this->assertContains($exchangeRate->getTo(), ['EUR', 'GBP']);
            $this->assertIsFloat($exchangeRate->getRate());
            $this->assertIsInt($exchangeRate->getTimestamp());
        }
    }

    public function test_convert(): void
    {
        $conversion = $this->client->convert('USD', 'PLN', 100.0);
        $this->assertEquals('USD', $conversion->getFrom());
        $this->assertEquals('PLN', $conversion->getTo());
        $this->assertIsFloat($conversion->getAmount());
        $this->assertIsFloat($conversion->getResult());
        $this->assertIsInt($conversion->getTimestamp());
    }

    public function test_convert_many(): void
    {
        $conversions = $this->client->convertMany('USD', ['EUR', 'GBP'], 100.0);
        $this->assertCount(2, $conversions);
        foreach ($conversions as $conversion) {
            $this->assertEquals('USD', $conversion->getFrom());
            $this->assertContains($conversion->getTo(), ['EUR', 'GBP']);
            $this->assertIsFloat($conversion->getAmount());
            $this->assertIsFloat($conversion->getResult());
            $this->assertIsInt($conversion->getTimestamp());
        }
    }
}
