<?php

declare(strict_types=1);

namespace ProbabilisticLaravel\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use ProbabilisticLaravel\Facades\Probabilistic;

final class ProbabilisticManagerTest extends TestCase
{
    public function testFacadeResolvesConfiguredBloomFilter(): void
    {
        $filter = Probabilistic::bloomFilter();
        $filter->add('a@example.com');

        self::assertTrue($filter->mightContain('a@example.com'));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testManagerIsSingletonAcrossResolutions(): void
    {
        $first = $this->app->make('probabilistic');
        $second = $this->app->make('probabilistic');

        self::assertSame($first, $second);
    }
}
