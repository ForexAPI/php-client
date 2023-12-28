<?php

declare(strict_types=1);

namespace ForexAPI\Client;

class ExchangeRate
{
    private string $from;
    private string $to;
    private int $timestamp;
    private float $rate;

    public function __construct(
        string $from,
        string $to,
        float $rate,
        int $timestamp
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->rate = $rate;
        $this->timestamp = $timestamp;
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
