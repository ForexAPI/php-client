<?php

declare(strict_types=1);

namespace ForexAPI\Client;

class UsageQuota
{
    private string $plan;
    private int $used;
    private int $limit;
    private int $remaining;

    public function __construct(string $plan, int $used, int $limit, int $remaining)
    {
        $this->plan = $plan;
        $this->used = $used;
        $this->limit = $limit;
        $this->remaining = $remaining;
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
