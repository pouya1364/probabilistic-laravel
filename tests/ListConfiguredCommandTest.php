<?php

declare(strict_types=1);

namespace ProbabilisticLaravel\Tests;

final class ListConfiguredCommandTest extends TestCase
{
    public function testCommandRunsSuccessfully(): void
    {
        $this->artisan('probabilistic:list')->assertSuccessful();
    }

    public function testCommandListsConfiguredInstances(): void
    {
        // The 'bloom_filters.default' instance is seeded in TestCase::defineEnvironment().
        $this->artisan('probabilistic:list')
            ->expectsOutputToContain('bloom_filters')
            ->expectsOutputToContain('default')
            ->assertSuccessful();
    }

    public function testCommandSkipsEmptyGroups(): void
    {
        // No cuckoo_filters are configured, so that group must not appear.
        $this->artisan('probabilistic:list')
            ->doesntExpectOutputToContain('cuckoo_filters')
            ->assertSuccessful();
    }
}
