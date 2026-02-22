<?php

namespace Rcommerz\Logger;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Rcommerz\Logger\Formatter\StructuredJsonFormatter;
use Rcommerz\Logger\Processor\TraceContextProcessor;

/**
 * Singleton Logger wrapper around Monolog
 * Provides structured JSON logging with OpenTelemetry support
 */
class Logger
{
    private static ?self $instance = null;
    private MonologLogger $monolog;
    private LoggerConfig $config;

    private function __construct(LoggerConfig $config)
    {
        $this->config = $config;
        $this->monolog = $this->createMonologInstance($config);
    }

    /**
     * Create and configure Monolog instance
     */
    private function createMonologInstance(LoggerConfig $config): MonologLogger
    {
        $logger = new MonologLogger('rcommerz');

        // Create custom formatter
        $formatter = new StructuredJsonFormatter(
            $config->serviceName,
            $config->serviceVersion,
            $config->env
        );

        // Create stream handler for stdout
        $handler = new StreamHandler(
            'php://stdout',
            $this->getMonologLevel($config->level)
        );
        $handler->setFormatter($formatter);

        // Add handlers and processors
        $logger->pushHandler($handler);
        $logger->pushProcessor(new TraceContextProcessor());

        return $logger;
    }

    /**
     * Convert log level string to Monolog constant
     */
    private function getMonologLevel(string $level): int
    {
        return match (strtoupper($level)) {
            'DEBUG' => MonologLogger::DEBUG,
            'WARN', 'WARNING' => MonologLogger::WARNING,
            'ERROR', 'CRITICAL' => MonologLogger::ERROR,
            default => MonologLogger::INFO,
        };
    }

    /**
     * Initialize the logger singleton
     */
    public static function initialize(LoggerConfig $config): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Get the logger singleton instance
     *
     * @throws \RuntimeException if logger not initialized
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            throw new \RuntimeException(
                'Logger not initialized. Call Logger::initialize() first.'
            );
        }
        return self::$instance;
    }

    /**
     * Reset the singleton instance (for testing)
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Get logger configuration
     */
    public function getConfig(): LoggerConfig
    {
        return $this->config;
    }

    /**
     * Get underlying Monolog instance
     */
    public function getMonolog(): MonologLogger
    {
        return $this->monolog;
    }

    /**
     * Build context with log type
     */
    private function buildContext(string $logType, array $context): array
    {
        return array_merge(['log_type' => $logType], $context);
    }

    /**
     * Process exception in context
     */
    private function processException(array $context): array
    {
        if (isset($context['error']) && $context['error'] instanceof \Throwable) {
            $exception = $context['error'];
            $context['error.message'] = $exception->getMessage();
            $context['error.type'] = get_class($exception);
            $context['error.file'] = $exception->getFile();
            $context['error.line'] = $exception->getLine();
            $context['error.stack_trace'] = $exception->getTraceAsString();
            unset($context['error']);
        }
        return $context;
    }

    /**
     * Log info level message
     */
    public function info(string $message, array $context = []): void
    {
        $this->monolog->info($message, $this->buildContext('normal', $context));
    }

    /**
     * Log error level message
     */
    public function error(string $message, array $context = []): void
    {
        $context = $this->processException($context);
        $this->monolog->error($message, $this->buildContext('error', $context));
    }

    /**
     * Log warning level message
     */
    public function warn(string $message, array $context = []): void
    {
        $this->monolog->warning($message, $this->buildContext('normal', $context));
    }

    /**
     * Log debug level message
     */
    public function debug(string $message, array $context = []): void
    {
        $this->monolog->debug($message, $this->buildContext('debug', $context));
    }

    /**
     * Log security event
     */
    public function security(string $message, array $context = []): void
    {
        $this->monolog->warning($message, $this->buildContext('security', $context));
    }

    /**
     * Log audit event
     */
    public function audit(string $message, array $context = []): void
    {
        $this->monolog->info($message, $this->buildContext('audit', $context));
    }

    /**
     * Log HTTP request/response
     */
    public function http(string $message, array $context = []): void
    {
        $this->monolog->info($message, $this->buildContext('http', $context));
    }
}
