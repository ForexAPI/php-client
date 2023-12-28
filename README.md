# ForexAPI PHP Client

This is a PHP client for the ForexAPI. It provides an easy-to-use interface for interacting with the ForexAPI's endpoints.

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

Use Composer to install the ForexAPI PHP Client:

```bash
composer require forexapi/client
```

## Usage

Create an instance of the `Client` class directly with your API key:

```php
use ForexAPI\Client\Client;

$apiKey = 'your-api-key';
$client = new Client($apiKey);
```

Create new instance using the `ForexAPI\Client\ForexAPIClientBuilder` class:

```php
use \ForexAPI\Client\ForexAPIClientBuilder;

$builder = (new ForexAPIClientBuilder())
    ->withApiKey('your-api-key')
    ->withBaseUri('https://forexapi.eu/api/')
    ->withHttpAdapter($yourCustomHttpAdapter)
    ->build()
;
```

Using your own PSR-18 client and PSR-17 request factory is optional. If you don't provide them, the client will try to discover them automatically.

```php
use \ForexAPI\Client\ForexAPIClientBuilder;

$builder = (new ForexAPIClientBuilder())
    ->withApiKey('your-api-key')
    ->withPsr18Client($yourCustomPsr18Client)
    ->withPsr17RequestFactory($yourCustomPsr17RequestFactory)
    ->build()
;
```

### Get Live Quote

To get a live quote for a specific currency pair:

```php
$quote = $client->getLiveQuote('USD', 'PLN');

echo $quote->getBase(); // USD
echo $quote->getCounter(); // PLN
echo $quote->getBid(); // Bid price
echo $quote->getAsk(); // Ask price
echo $quote->getMid(); // Mid price
echo $quote->getTimestamp(); // Timestamp
```

### Get Exchange Rate

To get the exchange rate between two currencies:

```php
$exchangeRate = $client->getExchangeRate('USD', 'PLN');

echo $exchangeRate->getFrom(); // USD
echo $exchangeRate->getTo(); // PLN
echo $exchangeRate->getRate(); // Exchange rate
echo $exchangeRate->getTimestamp(); // Timestamp
```

If you would like to get multiple exchange rates at once, you can use the `getExchangeRates` method.  
It accepts an array of counter currencies as an argument:

```php
$exchangeRate = $client->getExchangeRates('USD', ['PLN', 'EUR', 'GBP']);
```

### Convert Currency

To convert an amount from one currency to another:

```php
$conversion = $client->convert('USD', 'PLN', 100.0);

echo $conversion->getFrom(); // USD
echo $conversion->getTo(); // PLN
echo $conversion->getAmount(); // 100.0
echo $conversion->getResult(); // Converted amount in PLN
echo $conversion->getTimestamp(); // Timestamp
```

If you would like to convert to multiple currencies at once, you can use the `convertMany` method:

```php
$conversions = $client->convertMany('USD', 'PLN', [100.0, 200.0, 300.0]);
```

## Testing

This library includes a suite of unit tests. Run them using PHPUnit:

```bash
./vendor/bin/phpunit
```

## License

This project is licensed under the MIT License. See the LICENSE file for details.