<?php

declare(strict_types=1);

namespace ProbabilisticLaravel;

use Probabilistic\BloomFilter;
use Probabilistic\CountingBloomFilter;
use Probabilistic\CountMinSketch;
use Probabilistic\CuckooFilter;
use Probabilistic\HyperLogLog;
use RuntimeException;

final class ProbabilisticManager
{
    /** @var array<string, object> */
    private array $instances = [];

    public function __construct(
        private readonly array $config,
    ) {
    }

    public function bloomFilter(string $name = 'default'): BloomFilter
    {
        return $this->instances["bloom_filter.$name"] ??= BloomFilter::create(
            ...$this->definitionFor('bloom_filters', $name),
        );
    }

    public function countingBloomFilter(string $name = 'default'): CountingBloomFilter
    {
        return $this->instances["counting_bloom_filter.$name"] ??= CountingBloomFilter::create(
            ...$this->definitionFor('counting_bloom_filters', $name),
        );
    }

    public function cuckooFilter(string $name = 'default'): CuckooFilter
    {
        return $this->instances["cuckoo_filter.$name"] ??= CuckooFilter::create(
            ...$this->definitionFor('cuckoo_filters', $name),
        );
    }

    public function countMinSketch(string $name = 'default'): CountMinSketch
    {
        return $this->instances["count_min_sketch.$name"] ??= CountMinSketch::create(
            ...$this->definitionFor('count_min_sketches', $name),
        );
    }

    public function hyperLogLog(string $name = 'default'): HyperLogLog
    {
        return $this->instances["hyperloglog.$name"] ??= new HyperLogLog(
            ...$this->definitionFor('hyperloglogs', $name),
        );
    }

    public function all(): array
    {
        return $this->config;
    }

    /**
     * Config is written in snake_case (matching Laravel config convention), but the
     * underlying structures' factories/constructors take camelCase parameters and we
     * spread the definition as named arguments — so the keys must be translated first,
     * otherwise PHP raises "Unknown named parameter".
     *
     * @return array<string, mixed>
     */
    private function definitionFor(string $group, string $name): array
    {
        if (!isset($this->config[$group][$name])) {
            throw new RuntimeException(
                "No '$name' instance configured under '$group'. Check config/probabilistic.php."
            );
        }

        $definition = [];
        foreach ($this->config[$group][$name] as $key => $value) {
            $definition[self::toCamelCase($key)] = $value;
        }

        return $definition;
    }

    private static function toCamelCase(string $key): string
    {
        return ucwords( $key, '_' )
                |> (static fn($x) => str_replace( '_', '', $x ))
                |> lcfirst(...);
    }
}
