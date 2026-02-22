<?php

namespace Rcommerz\Logger\Tests;

use Rcommerz\Logger\LoggerConfig;

class LoggerConfigTest extends TestCase
{
    /** @test */
    public function it_creates_config_with_all_parameters()
    {
        $config = new LoggerConfig('my-service', '2.0.0', 'production', 'DEBUG');

        $this->assertEquals('my-service', $config->serviceName);
        $this->assertEquals('2.0.0', $config->serviceVersion);
        $this->assertEquals('production', $config->env);
        $this->assertEquals('DEBUG', $config->level);
    }

    /** @test */
    public function it_uses_default_log_level()
    {
        $config = new LoggerConfig('my-service', '1.0.0', 'test');

        $this->assertEquals('INFO', $config->level);
    }

    /** @test */
    public function it_creates_from_config()
    {
        // Config is already set in TestCase::getEnvironmentSetUp
        $config = LoggerConfig::fromEnv();

        $this->assertEquals('test-service', $config->serviceName);
        $this->assertEquals('1.0.0', $config->serviceVersion);
        $this->assertEquals('test', $config->env);
        $this->assertEquals('INFO', $config->level);
    }

    /** @test */
    public function it_handles_debug_level()
    {
        $config = new LoggerConfig('service', '1.0.0', 'dev', 'DEBUG');
        $this->assertEquals('DEBUG', $config->level);
    }

    /** @test */
    public function it_handles_info_level()
    {
        $config = new LoggerConfig('service', '1.0.0', 'dev', 'INFO');
        $this->assertEquals('INFO', $config->level);
    }

    /** @test */
    public function it_handles_warn_level()
    {
        $config = new LoggerConfig('service', '1.0.0', 'dev', 'WARN');
        $this->assertEquals('WARN', $config->level);
    }

    /** @test */
    public function it_handles_warning_level()
    {
        $config = new LoggerConfig('service', '1.0.0', 'dev', 'WARNING');
        $this->assertEquals('WARNING', $config->level);
    }

    /** @test */
    public function it_handles_error_level()
    {
        $config = new LoggerConfig('service', '1.0.0', 'dev', 'ERROR');
        $this->assertEquals('ERROR', $config->level);
    }

    /** @test */
    public function it_handles_development_env()
    {
        $config = new LoggerConfig('service', '1.0.0', 'development', 'INFO');
        $this->assertEquals('development', $config->env);
    }

    /** @test */
    public function it_handles_staging_env()
    {
        $config = new LoggerConfig('service', '1.0.0', 'staging', 'INFO');
        $this->assertEquals('staging', $config->env);
    }

    /** @test */
    public function it_handles_production_env()
    {
        $config = new LoggerConfig('service', '1.0.0', 'production', 'INFO');
        $this->assertEquals('production', $config->env);
    }

    /** @test */
    public function it_handles_test_env()
    {
        $config = new LoggerConfig('service', '1.0.0', 'test', 'INFO');
        $this->assertEquals('test', $config->env);
    }

    /** @test */
    public function config_properties_are_publicly_accessible()
    {
        $config = new LoggerConfig('svc', '1.2.3', 'prod', 'ERROR');

        $this->assertTrue(isset($config->serviceName));
        $this->assertTrue(isset($config->serviceVersion));
        $this->assertTrue(isset($config->env));
        $this->assertTrue(isset($config->level));
    }
}
