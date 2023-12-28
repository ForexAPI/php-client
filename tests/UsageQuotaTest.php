<?php

declare(strict_types=1);

namespace ForexAPI\Tests\Client;

use ForexAPI\Client\UsageQuota;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ForexAPI\Client\UsageQuota
 */
class UsageQuotaTest extends TestCase
{
    public function test_get_remaining(): void
    {
        $quota = new UsageQuota('plan', 1, 2, 3);
        $this->assertEquals(3, $quota->getRemaining());
    }

    public function test_get_used(): void
    {
        $quota = new UsageQuota('plan', 1, 2, 3);
        $this->assertEquals(1, $quota->getUsed());
    }

    public function test_get_plan(): void
    {
        $quota = new UsageQuota('plan', 1, 2, 3);
        $this->assertEquals('plan', $quota->getPlan());
    }

    public function test_get_limit(): void
    {
        $quota = new UsageQuota('plan', 1, 2, 3);
        $this->assertEquals(2, $quota->getLimit());
    }
}
