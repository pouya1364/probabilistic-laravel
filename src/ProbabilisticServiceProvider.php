<?php

declare(strict_types=1);

namespace ProbabilisticLaravel;

use Illuminate\Support\ServiceProvider;
use ProbabilisticLaravel\Console\ListConfiguredCommand;

final class ProbabilisticServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/probabilistic.php', 'probabilistic');

        $this->app->singleton(ProbabilisticManager::class, function ($app) {
            return new ProbabilisticManager($app['config']['probabilistic']);
        });
        $this->app->alias(ProbabilisticManager::class, 'probabilistic');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/probabilistic.php' => config_path('probabilistic.php'),
            ], 'probabilistic-config');

            $this->commands([
                ListConfiguredCommand::class,
            ]);
        }
    }
}
