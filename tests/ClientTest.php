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
use Psr\Http\Client\ClientInterface;

/**
 * @coversNothing
 */
class ClientTest extends TestCase
{
    private ClientInterface $httpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $namingStrategy = new PathNamingStrategy();
        $recorder = new FilesystemRecorder(__DIR__.'/recordings');
        $record = new RecordPlugin($namingStrategy, $recorder);
        $replay = new ReplayPlugin($namingStrategy, $recorder, false);

        $this->httpClient = new PluginClient(Psr18ClientDiscovery::find(), [$replay, $record]);
    }

    public function test_convert_multiple(): void
    {
    }

    public function test_get_live_quotes(): void
    {
        $apikey = '1C6Zj4cB7pTxidWugZHXof';
        $adapter = new PsrHttpAdapter($this->httpClient);
        $client = new Client($apikey, Client::BASE_URI, $adapter);
        $quotes = $client->getLiveQuotes('USD', ['EUR', 'GBP']);

        $this->assertCount(2, $quotes);
        $this->assertEquals('USD', $quotes[0]->getBase());
        $this->assertEquals('EUR', $quotes[0]->getCounter());
        $this->assertIsFloat($quotes[0]->getBid());
        $this->assertIsFloat($quotes[0]->getAsk());
        $this->assertIsFloat($quotes[0]->getMid());
        $this->assertIsInt($quotes[0]->getTimestamp());
        $this->assertSame(2, $quotes[0]->getCost());
    }

    public function test_get_currency_rates(): void
    {
    }

    public function test_convert(): void
    {
    }
}
