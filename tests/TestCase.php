<?php

namespace Rcommerz\Logger\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Rcommerz\Logger\LoggerServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LoggerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default environment config
        $app['config']->set('rcommerz_logger.service_name', 'test-service');
        $app['config']->set('rcommerz_logger.service_version', '1.0.0');
        $app['config']->set('rcommerz_logger.env', 'test');
        $app['config']->set('rcommerz_logger.level', 'INFO');
        $app['config']->set('rcommerz_logger.exclude_paths', ['/health', '/metrics']);
        $app['config']->set('rcommerz_logger.include_headers', false);
        $app['config']->set('rcommerz_logger.include_body', false);
    }
}
