<?php

declare(strict_types=1);

namespace ProbabilisticLaravel\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ProbabilisticLaravel\ProbabilisticManager;
use RuntimeException;

final class ProbabilisticManagerTest extends TestCase
{
    public function testBuildsConfiguredBloomFilter(): void
    {
        $manager = new ProbabilisticManager([
            'bloom_filters' => [
                'emails_seen' => ['expected_items' => 1000, 'false_positive_rate' => 0.01],
            ],
        ]);

        $filter = $manager->bloomFilter('emails_seen');
        $filter->add('a@example.com');

        self::assertTrue($filter->mightContain('a@example.com'));
    }

    public function testBuildsEveryStructureType(): void
    {
        $manager = new ProbabilisticManager([
            'bloom_filters' => ['default' => ['expected_items' => 1000, 'false_positive_rate' => 0.01]],
            'counting_bloom_filters' => ['default' => ['expected_items' => 1000, 'false_positive_rate' => 0.01]],
            'cuckoo_filters' => ['default' => ['expected_items' => 1000]],
            'count_min_sketches' => ['default' => ['width' => 2000, 'depth' => 5]],
            'hyperloglogs' => ['default' => ['precision' => 14]],
        ]);

        $bloom = $manager->bloomFilter();
        $bloom->add('x');
        self::assertTrue($bloom->mightContain('x'));

        $counting = $manager->countingBloomFilter();
        $counting->add('x');
        self::assertTrue($counting->mightContain('x'));

        $cuckoo = $manager->cuckooFilter();
        $cuckoo->add('x');
        self::assertTrue($cuckoo->contains('x'));

        $sketch = $manager->countMinSketch();
        $sketch->increment('x');
        self::assertSame(1, $sketch->estimate('x'));

        $hll = $manager->hyperLogLog();
        $hll->add('x');
        self::assertGreaterThan(0, $hll->estimate());
    }

    public function testReturnsSameInstanceOnRepeatedAccess(): void
    {
        $manager = new ProbabilisticManager([
            'bloom_filters' => ['default' => ['expected_items' => 1000, 'false_positive_rate' => 0.01]],
        ]);

        self::assertSame($manager->bloomFilter(), $manager->bloomFilter());
    }

    public function testDistinctNamesProduceDistinctInstances(): void
    {
        $manager = new ProbabilisticManager([
            'bloom_filters' => [
                'emails' => ['expected_items' => 1000, 'false_positive_rate' => 0.01],
                'usernames' => ['expected_items' => 2000, 'false_positive_rate' => 0.01],
            ],
        ]);

        self::assertNotSame($manager->bloomFilter('emails'), $manager->bloomFilter('usernames'));
    }

    public function testThrowsForUnknownName(): void
    {
        $manager = new ProbabilisticManager(['bloom_filters' => []]);

        $this->expectException(RuntimeException::class);
        $manager->bloomFilter('does_not_exist');
    }

    /**
     * The structures' factories take camelCase parameters while config is snake_case;
     * this guards the translation that makes the named-argument spread line up. If the
     * keys were not translated, the named-argument spread would raise an error before a
     * usable filter could be built and queried.
     */
    public function testSnakeCaseConfigKeysTranslateToCamelCaseParameters(): void
    {
        $manager = new ProbabilisticManager([
            'bloom_filters' => [
                'default' => ['expected_items' => 5000, 'false_positive_rate' => 0.001],
            ],
        ]);

        $filter = $manager->bloomFilter();
        $filter->add('token');

        self::assertTrue($filter->mightContain('token'));
    }

    public function testAllReturnsFullConfig(): void
    {
        $config = [
            'bloom_filters' => ['default' => ['expected_items' => 1000, 'false_positive_rate' => 0.01]],
        ];

        self::assertSame($config, new ProbabilisticManager($config)->all());
    }
}
