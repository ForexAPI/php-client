[![logo.png](res/banner.png)](https://forexapi.eu)

# ForexAPI PHP Client

This is a PHP client for the **ForexAPI**. It provides an easy-to-use interface for interacting with the API's endpoints. 
The **ForexAPI** offers a free plan and provides foreign exchange rates and currency conversion.  
The API documentation can be found at [https://forexapi.eu/en/docs](https://forexapi.eu/en/docs).

[Get a free API key](https://forexapi.eu/)

## Requirements

- PHP 7.4 (branch 1.x)
- PHP 8.3 (branch 2.x)
- Composer

## Installation

Use Composer to install the ForexAPI PHP Client:

```bash
composer require forexapi/client
```

Version 2.x of this library requires PHP 8.3 or higher. If you need to use PHP 7.4, install version 1.x.  
Both versions are supported and maintained.


```bash
composer require forexapi/client:^1.0
```

This package does not come with a Http Client. [You can use any PSR-18 compatible client](https://packagist.org/providers/psr/http-client-implementation).  
If you have multiple Http Clients installed, [you can specify which one to use](https://github.com/php-http/discovery?tab=readme-ov-file#usage-as-a-library-user).

```bash
composer require guzzlehttp/guzzle forexapi/client
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
$conversions = $client->convertMany('USD', ['PLN', 'EUR', 'GBP'], 100.0);
```

## Testing

This library includes a suite of unit tests. Run them using PHPUnit:

```bash
./vendor/bin/phpunit
```

## License

This project is licensed under the MIT License. See the LICENSE file for details.