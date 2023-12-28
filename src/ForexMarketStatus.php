<?php

declare(strict_types=1);

namespace ForexAPI\Client;

class ForexMarketStatus
{
    private bool $isOpen;

    public function __construct(bool $isOpen)
    {
        $this->isOpen = $isOpen;
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }
}
