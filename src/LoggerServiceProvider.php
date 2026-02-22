<?php

namespace Rcommerz\Logger;

use Illuminate\Support\ServiceProvider;

/**
 * Logger Service Provider
 *
 * @property \Illuminate\Contracts\Foundation\Application $app
 */
class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config from file
        $this->mergeConfigFrom(
            __DIR__ . '/../config/rcommerz_logger.php',
            'rcommerz_logger'
        );

        $this->app->singleton(Logger::class, function ($app) {
            $config = LoggerConfig::fromEnv();
            return Logger::initialize($config);
        });

        // Register alias
        $this->app->alias(Logger::class, 'rcommerz.logger');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/rcommerz_logger.php' => config_path('rcommerz_logger.php'),
        ], 'rcommerz-logger-config');
    }
}
