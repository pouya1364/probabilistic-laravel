# probabilistic-laravel

Laravel integration for [`pouya1364/probabilistic-php`](https://packagist.org/packages/pouya1364/probabilistic-php). Define named, pre-configured probabilistic data structures once in config, then resolve them anywhere in your app — the same pattern Laravel uses for cache stores, queue connections, and database connections.

Supported structures: Bloom Filter, Counting Bloom Filter, Cuckoo Filter, Count-Min Sketch, and HyperLogLog. See the [core library](https://packagist.org/packages/pouya1364/probabilistic-php) for the algorithm documentation.

## Requirements

- PHP 8.2+
- Laravel 10, 11, 12, or 13

## Installation

```bash
composer require pouya1364/probabilistic-laravel
```

The service provider and the `Probabilistic` facade are registered automatically via package auto-discovery — no manual registration needed.

Publish the config file:

```bash
php artisan vendor:publish --tag=probabilistic-config
```

This creates `config/probabilistic.php`.

## Configuration

Each structure type holds any number of independently named instances, so a single app can size several filters differently for different purposes. Keys are written in `snake_case`.

```php
// config/probabilistic.php

return [
    'bloom_filters' => [
        'emails_seen' => ['expected_items' => 100000, 'false_positive_rate' => 0.01],
    ],
    'counting_bloom_filters' => [
        'active_sessions' => ['expected_items' => 50000, 'false_positive_rate' => 0.01],
    ],
    'cuckoo_filters' => [
        'rate_limited_ips' => ['expected_items' => 100000],
    ],
    'count_min_sketches' => [
        'page_view_counts' => ['width' => 2000, 'depth' => 5],
    ],
    'hyperloglogs' => [
        'unique_visitors' => ['precision' => 14],
    ],
];
```

| Group | Parameters |
| --- | --- |
| `bloom_filters` | `expected_items` (int), `false_positive_rate` (float) |
| `counting_bloom_filters` | `expected_items` (int), `false_positive_rate` (float) |
| `cuckoo_filters` | `expected_items` (int) |
| `count_min_sketches` | `width` (int), `depth` (int) |
| `hyperloglogs` | `precision` (int, default 14) |

Each instance is built once, on first access, and reused for the lifetime of the request.

## Usage

### Facade

```php
use ProbabilisticLaravel\Facades\Probabilistic;

$emails = Probabilistic::bloomFilter('emails_seen');
$emails->add('jane@example.com');

if ($emails->mightContain('jane@example.com')) {
    // probably seen before
}

Probabilistic::hyperLogLog('unique_visitors')->add($visitorId);
$estimate = Probabilistic::hyperLogLog('unique_visitors')->estimate();
```

Available accessors (each takes an optional instance name, defaulting to `'default'`):

```php
Probabilistic::bloomFilter('emails_seen');
Probabilistic::countingBloomFilter('active_sessions');
Probabilistic::cuckooFilter('rate_limited_ips');
Probabilistic::countMinSketch('page_view_counts');
Probabilistic::hyperLogLog('unique_visitors');
```

The facade exposes a `@method static` docblock for each accessor, so IDEs autocomplete the methods and their return types.

### Without the facade

If you prefer not to use facades, resolve the manager from the container or inject it directly — both return the same singleton instance:

```php
// Container
$manager = app('probabilistic');
$filter = $manager->bloomFilter('emails_seen');
```

```php
use ProbabilisticLaravel\ProbabilisticManager;

final class SignupController
{
    public function __construct(private readonly ProbabilisticManager $probabilistic) {}

    public function store(): void
    {
        $this->probabilistic->bloomFilter('emails_seen')->add(/* ... */);
    }
}
```

## Listing configured instances

```bash
php artisan probabilistic:list
```

Prints a table of every configured instance and its parameters, grouped by structure type. Empty groups are omitted.

## Testing

```bash
composer test
```

## License

MIT
