<?php

namespace ForexAPI\Tests\Client;

use ForexAPI\Client\Client;
use ForexAPI\Client\HttpAdapter;
use ForexAPI\Client\PsrHttpAdapter;
use Http\Client\Common\PluginClient;
use Http\Client\Plugin\Vcr\NamingStrategy\PathNamingStrategy;
use Http\Client\Plugin\Vcr\Recorder\FilesystemRecorder;
use Http\Client\Plugin\Vcr\RecordPlugin;
use Http\Client\Plugin\Vcr\ReplayPlugin;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Discovery\Strategy\MockClientStrategy;
use Http\Message\RequestMatcher\RequestMatcher;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class ClientTest extends TestCase
{
    private ClientInterface $client;

    protected function setUp(): void
    {
        parent::setUp();

        $namingStrategy = new PathNamingStrategy();
        $recorder = new FilesystemRecorder(__DIR__ . '/recordings');
        $record = new RecordPlugin($namingStrategy, $recorder);
        $replay = new ReplayPlugin($namingStrategy, $recorder, false);

        $this->client = new PluginClient(Psr18ClientDiscovery::find(),[$replay, $record]);
    }

    public function testConvertMultiple()
    {

    }

    public function testGetLiveQuotes()
    {
        $apikey = '1C6Zj4cB7pTxidWugZHXof';
        $adapter = new PsrHttpAdapter($this->client);
        $client = new Client($apikey, Client::BASE_URI, $adapter);
        $client->getLiveQuotes('USD', ['EUR', 'GBP']);
    }

    public function testGetCurrencyRates()
    {

    }

    public function testConvert()
    {

    }
}
