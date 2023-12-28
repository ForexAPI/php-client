<?php

declare(strict_types=1);

namespace ForexAPI\Client;

readonly class UsageQuota
{
    public function __construct(
        private string $plan,
        private int $used,
        private int $limit,
        private int $remaining
    ) {
    }

    public function getPlan(): string
    {
        return $this->plan;
    }

    public function getUsed(): int
    {
        return $this->used;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getRemaining(): int
    {
        return $this->remaining;
    }
}
