<?php

declare(strict_types=1);

namespace ForexAPI\Client;

readonly class LiveQuote
{
    public function __construct(
        private string $base,
        private string $counter,
        private float $bid,
        private float $ask,
        private float $mid,
        private int $timestamp
    ) {
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getCounter(): string
    {
        return $this->counter;
    }

    public function getBid(): float
    {
        return $this->bid;
    }

    public function getAsk(): float
    {
        return $this->ask;
    }

    public function getMid(): float
    {
        return $this->mid;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
