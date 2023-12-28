<?php

declare(strict_types=1);

namespace ForexAPI\Client;

readonly class ForexMarketStatus
{
    public function __construct(private bool $isOpen)
    {
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }
}
