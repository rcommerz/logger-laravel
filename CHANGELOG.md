# Changelog

All notable changes to `rcommerz/logger-laravel` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2026-02-22

### Added
- Initial release of RCOMMERZ Logger for Laravel
- Structured JSON logging with ECS-compatible format
- Custom Monolog formatter (`StructuredJsonFormatter`)
- OpenTelemetry trace context processor (`TraceContextProcessor`)
- Singleton Logger class with clean API
- HTTP request/response logging middleware
- Automatic sensitive header redaction (Authorization, Cookie, API keys)
- Laravel service provider with auto-discovery
- Comprehensive configuration via environment variables
- Support for Laravel 10.x and 11.x
- Support for PHP 8.1+
- Monolog 3.5+ integration
- Log levels: debug, info, warn, error, http, security, audit
- Path exclusion for health/metrics endpoints
- Optional header and body logging
- Facade support for convenient access
- 31 tests with 62 assertions (100% passing)
- Comprehensive README with usage examples
- MIT License
- IDE helper files for PHPStorm and VSCode

### Features
- **Single-line JSON output** to stdout for container-friendly logging
- **ECS field naming** (service.name, http.method, client.ip, etc.)
- **Match expression** for status code logging levels
- **Safe request body parsing** with error handling
- **Graceful OpenTelemetry handling** when unavailable
- **Type-safe implementation** with PHP 8.1+ type hints

### Security
- Automatic redaction of sensitive headers
- Safe exception handling with proper error formatting
- No credential exposure in logs

[Unreleased]: https://github.com/rcommerz/logger-laravel/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/rcommerz/logger-laravel/releases/tag/v1.0.0
