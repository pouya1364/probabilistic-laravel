<?php

declare(strict_types=1);

namespace ProbabilisticLaravel\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use ProbabilisticLaravel\ProbabilisticManager;

final class ServiceProviderTest extends TestCase
{
    public function testManagerIsBoundInContainer(): void
    {
        self::assertTrue($this->app->bound(ProbabilisticManager::class));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testAliasResolvesToManager(): void
    {
        self::assertInstanceOf(ProbabilisticManager::class, $this->app->make('probabilistic'));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testManagerIsRegisteredAsSingleton(): void
    {
        self::assertSame($this->app->make('probabilistic'), $this->app->make('probabilistic'));
    }

    public function testPackageConfigIsMerged(): void
    {
        self::assertSame(
            ['expected_items' => 1000, 'false_positive_rate' => 0.01],
            $this->app['config']->get('probabilistic.bloom_filters.default'),
        );
    }
}
