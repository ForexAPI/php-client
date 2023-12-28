<?php

declare(strict_types=1);

namespace ForexAPI\Client;

readonly class ExchangeRate
{
    public function __construct(
        private string $from,
        private string $to,
        private float $rate,
        private int $timestamp
    ) {
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
