<?php

declare(strict_types=1);

namespace ForexAPI\Tests\Client;

use ForexAPI\Client\LiveQuote;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ForexAPI\Client\LiveQuote
 */
class LiveQuoteTest extends TestCase
{
    public function test_get_bid(): void
    {
        $quote = new LiveQuote('EUR', 'USD', 1.0, 1.0, 1.0, 1);
        $this->assertEquals(1.0, $quote->getBid());
    }

    public function test_get_base(): void
    {
        $quote = new LiveQuote('EUR', 'USD', 1.0, 1.0, 1.0, 1);
        $this->assertEquals('EUR', $quote->getBase());
    }

    public function test_get_timestamp(): void
    {
        $quote = new LiveQuote('EUR', 'USD', 1.0, 1.0, 1.0, 1);
        $this->assertEquals(1, $quote->getTimestamp());
    }

    public function test_get_ask(): void
    {
        $quote = new LiveQuote('EUR', 'USD', 1.0, 1.0, 1.0, 1);
        $this->assertEquals(1.0, $quote->getAsk());
    }

    public function test_get_counter(): void
    {
        $quote = new LiveQuote('EUR', 'USD', 1.0, 1.0, 1.0, 1);
        $this->assertEquals('USD', $quote->getCounter());
    }

    public function test_get_mid(): void
    {
        $quote = new LiveQuote('EUR', 'USD', 1.0, 1.0, 1.0, 1);
        $this->assertEquals(1.0, $quote->getMid());
    }
}
