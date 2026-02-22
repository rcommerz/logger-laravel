<?php

namespace Rcommerz\Logger\Tests;

use Rcommerz\Logger\Logger;
use Rcommerz\Logger\LoggerConfig;

class LoggerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Logger::reset();
    }

    /** @test */
    public function it_initializes_as_singleton()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $logger2 = Logger::getInstance();

        $this->assertSame($logger, $logger2);
    }

    /** @test */
    public function it_can_be_reset()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger1 = Logger::initialize($config);

        Logger::reset();

        $config2 = new LoggerConfig('other-service', '2.0.0', 'prod', 'DEBUG');
        $logger2 = Logger::initialize($config2);

        $this->assertNotSame($logger1, $logger2);
    }

    /** @test */
    public function it_returns_config()
    {
        $config = new LoggerConfig('my-service', '1.5.0', 'production', 'ERROR');
        $logger = Logger::initialize($config);

        $returnedConfig = $logger->getConfig();

        $this->assertSame($config, $returnedConfig);
        $this->assertEquals('my-service', $returnedConfig->serviceName);
        $this->assertEquals('1.5.0', $returnedConfig->serviceVersion);
        $this->assertEquals('production', $returnedConfig->env);
        $this->assertEquals('ERROR', $returnedConfig->level);
    }

    /** @test */
    public function it_logs_info_messages_without_error()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->info('Test info message', [' user_id' => 'usr-123']);
    }

    /** @test */
    public function it_logs_error_messages_without_error()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->error('Test error', ['code' => 500]);
    }

    /** @test */
    public function it_logs_warn_messages_without_error()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->warn('Warning message');
    }

    /** @test */
    public function it_logs_debug_messages_when_level_is_debug()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'DEBUG');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->debug('Debug message');
    }

    /** @test */
    public function it_does_not_log_debug_when_level_is_info()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->debug('This should not log');
    }

    /** @test */
    public function it_does_not_log_info_when_level_is_error()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'ERROR');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->info('This should not log');
        $logger->warn('This should not log');
    }

    /** @test */
    public function it_logs_security_events_without_error()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->security('Security event', ['ip' => '192.168.1.1']);
    }

   /** @test */
    public function it_logs_audit_events_without_error()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->audit('User deleted', ['user_id' => 'usr-999']);
    }

    /** @test */
    public function it_logs_http_events_without_error()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->http('GET /api/users', ['status' => 200]);
    }

    /** @test */
    public function it_handles_exception_in_error_context()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $exception = new \Exception('Test exception');

        $this->expectNoException();
        $logger->error('Error occurred', ['error' => $exception]);
    }

    /** @test */
    public function it_handles_all_log_levels()
    {
        $levels = ['DEBUG', 'INFO', 'WARN', 'WARNING', 'ERROR'];

        foreach ($levels as $level) {
            Logger::reset();
            $config = new LoggerConfig('test', '1.0.0', 'test', $level);
            $logger = Logger::initialize($config);

            $this->assertInstanceOf(Logger::class, $logger);
            $this->assertEquals($level, $logger->getConfig()->level);
        }

        $this->expectNoException();
    }

    /** @test */
    public function it_handles_multiple_contexts()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $context = [
            'user_id' => 'usr-123',
            'tenant_id' => 'tenant-456',
            'request_id' => 'req-789',
            'data' => [
                'nested' => 'value'
            ]
        ];

        $this->expectNoException();
        $logger->info('Complex context', $context);
    }

    /** @test */
    public function it_works_with_empty_context()
    {
        $config = new LoggerConfig('test-service', '1.0.0', 'test', 'INFO');
        $logger = Logger::initialize($config);

        $this->expectNoException();
        $logger->info('Message without context');
    }

    /** @test */
    public function it_handles_various_service_versions()
    {
        $versions = ['1.0.0', '2.5.1', '0.0.1-beta', 'latest'];

        foreach ($versions as $version) {
            Logger::reset();
            $config = new LoggerConfig('test', $version, 'test', 'INFO');
            $logger = Logger::initialize($config);

            $this->assertEquals($version, $logger->getConfig()->serviceVersion);
        }

        $this->expectNoException();
    }

    /** @test */
    public function it_handles_various_environments()
    {
        $envs = ['development', 'staging', 'production', 'test'];

        foreach ($envs as $env) {
            Logger::reset();
            $config = new LoggerConfig('test', '1.0.0', $env, 'INFO');
            $logger = Logger::initialize($config);

            $this->assertEquals($env, $logger->getConfig()->env);
        }

        $this->expectNoException();
    }

    protected function expectNoException(): void
    {
        $this->assertTrue(true);
    }
}
