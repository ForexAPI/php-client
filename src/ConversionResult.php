<?php

declare(strict_types=1);

namespace ForexAPI\Client;

class ConversionResult
{
    public function __construct(
        string $from,
        string $to,
        float $amount,
        float $result,
        int $timestamp
    ) {
    }
}
