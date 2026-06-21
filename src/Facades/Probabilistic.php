<?php

declare(strict_types=1);

namespace ProbabilisticLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Probabilistic\BloomFilter;
use Probabilistic\CountingBloomFilter;
use Probabilistic\CountMinSketch;
use Probabilistic\CuckooFilter;
use Probabilistic\HyperLogLog;

/**
 * @method static BloomFilter bloomFilter(string $name = 'default')
 * @method static CountingBloomFilter countingBloomFilter(string $name = 'default')
 * @method static CuckooFilter cuckooFilter(string $name = 'default')
 * @method static CountMinSketch countMinSketch(string $name = 'default')
 * @method static HyperLogLog hyperLogLog(string $name = 'default')
 *
 * @see \ProbabilisticLaravel\ProbabilisticManager
 */
final class Probabilistic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'probabilistic';
    }
}
