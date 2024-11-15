<?php

declare(strict_types=1);

namespace Tests\Traits;

trait AssertsRandomUniqueQuotes
{
    public function assertRandomUniqueQuotes(array $quotes, int $count): void
    {
        $this->assertCount($count, $quotes);

        $found = [];

        foreach ($quotes as $quote) {
            $this->assertIsString($quote);
            $this->assertNotEmpty($quote);

            if (isset($found[$quote])) {
                $found[$quote] += 1;
            } else {
                $found[$quote] = 1;
            }
        }

        // Make sure quotes are unique
        foreach ($found as $count) {
            $this->assertEquals(1, $count);
        }
    }
}
