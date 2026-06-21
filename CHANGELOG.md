# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-06-21

### Added

- Service container integration for `pouya1364/probabilistic-php` via `ProbabilisticServiceProvider`, with package auto-discovery.
- `ProbabilisticManager` for resolving named, pre-configured Bloom filters, counting Bloom filters, cuckoo filters, count-min sketches, and HyperLogLogs, lazily built and cached per request.
- `Probabilistic` facade for static access to configured instances.
- Publishable `config/probabilistic.php` defining named instances per structure type.
- `probabilistic:list` Artisan command to inspect configured instances.

[0.1.0]: https://github.com/pouya1364/probabilistic-laravel/releases/tag/v0.1.0
