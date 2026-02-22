# RCOMMERZ Logger for Laravel

[![Tests](https://img.shields.io/badge/tests-31%20passing-brightgreen)]()
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)]()
[![Laravel](https://img.shields.io/badge/laravel-%5E10.0%20%7C%20%5E11.0-red)]()
[![License](https://img.shields.io/badge/license-MIT-green)]()

Production-ready structured JSON logging for Laravel microservices with OpenTelemetry support. Built on top of Monolog with ECS (Elastic Common Schema) compatible output.

## Features

- ✅ **Structured JSON Logging** - ECS-compatible format for seamless integration with Elasticsearch, Datadog, and other log aggregators
- ✅ **OpenTelemetry Integration** - Automatic trace context injection (trace_id, span_id, trace_flags)
- ✅ **HTTP Request/Response Logging** - Middleware for automatic API logging with configurable filtering
- ✅ **Security Built-in** - Automatic sensitive header redaction (Authorization, Cookie, API keys)
- ✅ **Laravel Auto-Discovery** - Zero configuration needed, works out of the box
- ✅ **Monolog Based** - Extends Monolog with custom formatter and processor for maximum flexibility
- ✅ **Type-Safe** - Fully typed with PHP 8.1+ features
- ✅ **100% Test Coverage** - 31 tests, 62 assertions, production-ready

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- Monolog 3.5+

## Installation

Install via Composer:

```bash
composer require rcommerz/logger-laravel
```

The service provider will be automatically registered thanks to Laravel's package auto-discovery.

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=rcommerz-logger-config
```

This creates `config/rcommerz_logger.php` where you can customize settings.

## Quick Start

### Basic Usage

```php
use Rcommerz\Logger\Logger;

// Get logger instance (singleton)
$logger = Logger::getInstance();

// Log messages with context
$logger->info('User logged in', ['user_id' => 'usr-123', 'email' => 'user@example.com']);
$logger->error('Payment failed', ['order_id' => 'ord-456', 'error' => 'Insufficient funds']);
$logger->debug('Cache hit', ['key' => 'user:123', 'ttl' => 3600]);
```

### Using Facade

```php
use Rcommerz\Logger\Facades\Logger;

Logger::info('Order created', ['order_id' => 'ord-789']);
Logger::security('Unauthorized access attempt', ['ip' => request()->ip()]);
Logger::audit('User role changed', ['user_id' => 'usr-123', 'old_role' => 'user', 'new_role' => 'admin']);
```

### HTTP Middleware

Add to your middleware stack to automatically log all HTTP requests/responses:

```php
// app/Http/Kernel.php
protected $middleware = [
    // ... other middleware
    \Rcommerz\Logger\Middleware\HttpLoggerMiddleware::class,
];
```

Or apply to specific routes:

```php
// routes/api.php
Route::middleware(['api', \Rcommerz\Logger\Middleware\HttpLoggerMiddleware::class])
    ->group(function () {
        Route::get('/users', [UserController::class, 'index']);
    });
```

## Configuration

### Environment Variables

Configure via `.env`:

```env
# Service Identity
SERVICE_NAME=my-laravel-app
SERVICE_VERSION=1.0.0
APP_ENV=production

# Logging Level (DEBUG, INFO, WARNING, ERROR)
LOG_LEVEL=INFO

# HTTP Middleware Options
LOG_INCLUDE_HEADERS=false
LOG_INCLUDE_BODY=false
```

### Configuration File

After publishing, edit `config/rcommerz_logger.php`:

```php
return [
    'service_name' => env('SERVICE_NAME', config('app.name', 'laravel-app')),
    'service_version' => env('SERVICE_VERSION', '1.0.0'),
    'env' => env('APP_ENV', 'production'),
    'level' => env('LOG_LEVEL', 'INFO'),
    
    // Paths to exclude from HTTP logging
    'exclude_paths' => [
        'health',
        'metrics',
        'api/health',
        'api/metrics',
    ],
    
    'include_headers' => env('LOG_INCLUDE_HEADERS', false),
    'include_body' => env('LOG_INCLUDE_BODY', false),
];
```

## Available Log Levels

```php
$logger->debug('Debug information');    // DEBUG
$logger->info('Informational message'); // INFO
$logger->warn('Warning message');       // WARNING
$logger->error('Error occurred');       // ERROR
$logger->http('HTTP request');          // INFO with http context
$logger->security('Security event');    // WARNING with security flag
$logger->audit('Audit trail');          // INFO with audit flag
```

## Log Output Format

All logs are output as single-line JSON to stdout in ECS-compatible format:

```json
{
  "@timestamp": "2026-02-22T18:30:45.123Z",
  "log.level": "info",
  "message": "User logged in",
  "service.name": "my-laravel-app",
  "service.version": "1.0.0",
  "service.environment": "production",
  "user_id": "usr-123",
  "email": "user@example.com",
  "trace_id": "4bf92f3577b34da6a3ce929d0e0e4736",
  "span_id": "00f067aa0ba902b7",
  "trace_flags": "01"
}
```

### HTTP Request Log Example

```json
{
  "@timestamp": "2026-02-22T18:30:45.123Z",
  "log.level": "info",
  "message": "POST /api/users - 201",
  "service.name": "my-laravel-app",
  "http.method": "POST",
  "http.path": "/api/users",
  "http.status_code": 201,
  "http.duration_ms": 45.23,
  "client.ip": "192.168.1.100",
  "user.id": "usr-123",
  "trace_id": "4bf92f3577b34da6a3ce929d0e0e4736"
}
```

## OpenTelemetry Integration

The logger automatically detects and includes OpenTelemetry trace context when available. Install OpenTelemetry SDK:

```bash
composer require open-telemetry/sdk
```

Configure OpenTelemetry instrumentation, and trace context will be automatically injected into all logs.

## Advanced Usage

### Access Underlying Monolog Instance

```php
$monolog = Logger::getInstance()->getMonolog();

// Add custom handlers
$monolog->pushHandler(new \Monolog\Handler\SlackWebhookHandler($webhookUrl));

// Add custom processors
$monolog->pushProcessor(function ($record) {
    $record->extra['custom_field'] = 'value';
    return $record;
});
```

### Exception Logging

Exceptions are automatically formatted with detailed information:

```php
try {
    processPayment($order);
} catch (\Exception $e) {
    $logger->error('Payment processing failed', [
        'order_id' => $order->id,
        'exception' => $e  // Automatically extracts: error.type, error.message, error.stack_trace
    ]);
}
```

### Context Preservation

Add persistent context to all subsequent log calls:

```php
use Monolog\Processor\UidProcessor;

$logger->getMonolog()->pushProcessor(new UidProcessor());
```

## Testing

The package includes comprehensive tests:

```bash
# Install dependencies
composer install

# Run tests
vendor/bin/phpunit

# Run tests with coverage
vendor/bin/phpunit --coverage-html coverage
```

## Security

### Automatic Sensitive Header Redaction

The HTTP middleware automatically redacts these headers:
- `Authorization`
- `Cookie`
- `X-API-Key`
- `X-Auth-Token`

### Custom Sensitive Data Filtering

Extend the middleware to add custom filtering:

```php
class CustomHttpLogger extends \Rcommerz\Logger\Middleware\HttpLoggerMiddleware
{
    protected function buildContext($request, $response, $duration): array
    {
        $context = parent::buildContext($request, $response, $duration);
        
        // Remove sensitive fields from body
        if (isset($context['http.request.body.password'])) {
            $context['http.request.body.password'] = '[REDACTED]';
        }
        
        return $context;
    }
}
```

## Comparison with Laravel's Default Logging

| Feature | RCOMMERZ Logger | Laravel Default |
|---------|----------------|-----------------|
| Structured JSON | ✅ ECS-compatible | ❌ Plain text/JSON mix |
| OpenTelemetry | ✅ Automatic | ❌ Manual setup |
| HTTP Logging | ✅ Middleware included | ❌ Manual |
| Sensitive Data Redaction | ✅ Automatic | ❌ Manual |
| Microservices Ready | ✅ Built-in | ⚠️ Requires config |
| Single-line JSON | ✅ Always | ❌ Multi-line |

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for all changes.

## License

This package is open-source software licensed under the [MIT license](LICENSE).

## Credits

- Built with [Monolog](https://github.com/Seldaek/monolog)
- OpenTelemetry integration via [open-telemetry/api](https://github.com/open-telemetry/opentelemetry-php)
- Inspired by [Elastic Common Schema](https://www.elastic.co/guide/en/ecs/current/index.html)

## Support

- **Issues**: [GitHub Issues](https://github.com/rcommerz/logger-laravel/issues)
- **Discussions**: [GitHub Discussions](https://github.com/rcommerz/logger-laravel/discussions)
- **Email**: dev@rcommerz.com
