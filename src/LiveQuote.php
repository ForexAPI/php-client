<?php

namespace ForexAPI\Client;

class LiveQuote
{
    public function __construct(
        string $base,
        string $counter,
        float  $bid,
        float  $ask,
        float  $mid,
        int    $timestamp,
        int $cost
    )
    {
    }
}