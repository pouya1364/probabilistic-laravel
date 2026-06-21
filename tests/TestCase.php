<?php

declare(strict_types=1);

namespace ProbabilisticLaravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use ProbabilisticLaravel\ProbabilisticServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [ProbabilisticServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('probabilistic.bloom_filters.default', [
            'expected_items' => 1000,
            'false_positive_rate' => 0.01,
        ]);
    }
}
