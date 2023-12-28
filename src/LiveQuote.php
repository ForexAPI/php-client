<?php

declare(strict_types=1);

namespace ForexAPI\Client;

class LiveQuote
{
    private string $base;
    private string $counter;
    private float $bid;
    private float $ask;
    private float $mid;
    private int $timestamp;
    private int $cost;

    public function __construct(
        string $base,
        string $counter,
        float $bid,
        float $ask,
        float $mid,
        int $timestamp,
        int $cost
    ) {
        $this->base = $base;
        $this->counter = $counter;
        $this->bid = $bid;
        $this->ask = $ask;
        $this->mid = $mid;
        $this->timestamp = $timestamp;
        $this->cost = $cost;
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

    public function getCost(): int
    {
        return $this->cost;
    }
}
