<?php

declare(strict_types=1);

namespace ForexAPI\Client;

class ConversionResult
{
    private string $from;
    private string $to;
    private float $amount;
    private float $result;
    private int $timestamp;

    public function __construct(
        string $from,
        string $to,
        float $amount,
        float $result,
        int $timestamp
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
        $this->result = $result;
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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getResult(): float
    {
        return $this->result;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
