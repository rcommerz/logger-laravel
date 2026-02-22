# Contributing to RCOMMERZ Logger for Laravel

Thank you for considering contributing to this package! This document provides guidelines for contributing.

## Code of Conduct

- Be respectful and inclusive
- Welcome newcomers and help them get started
- Focus on constructive feedback
- Respect differing viewpoints and experiences

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues. When creating a bug report, include:

- **Clear title and description**: Explain the problem clearly
- **Steps to reproduce**: List exact steps to reproduce the issue
- **Expected behavior**: What you expected to happen
- **Actual behavior**: What actually happened
- **Environment details**: PHP version, Laravel version, package version
- **Code samples**: Minimal code to reproduce the issue
- **Stack trace**: If applicable, include error messages

Example:

```markdown
## Bug: Logger not outputting to stdout

**Environment:**
- PHP 8.1.15
- Laravel 10.48.0
- rcommerz/logger-laravel 1.0.0

**Steps to reproduce:**
1. Install package via composer
2. Call `Logger::getInstance()->info('test')`
3. Check stdout output

**Expected:** JSON log line in stdout
**Actual:** No output

**Code:**
\```php
$logger = Logger::getInstance();
$logger->info('Test message');
\```
```

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. Include:

- **Use case**: Why is this enhancement needed?
- **Proposed solution**: How should it work?
- **Alternatives considered**: Other approaches you've thought about
- **Examples**: Code examples showing the desired API

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Write tests** for your changes
3. **Ensure tests pass** by running `vendor/bin/phpunit`
4. **Follow coding standards** (PSR-12)
5. **Update documentation** if needed
6. **Commit with clear messages** following conventional commits
7. **Submit pull request** with detailed description

#### Pull Request Guidelines

- One feature/fix per pull request
- Include tests for new functionality
- Maintain backward compatibility when possible
- Update CHANGELOG.md under [Unreleased] section
- Ensure CI passes (all tests, code style)

## Development Setup

### Prerequisites

- PHP 8.1 or higher
- Composer 2.x
- Git

### Getting Started

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/logger-laravel.git
cd logger-laravel

# Install dependencies
composer install

# Run tests
vendor/bin/phpunit

# Run tests with coverage
vendor/bin/phpunit --coverage-html coverage
```

### Project Structure

```
src/
├── Logger.php                    # Main logger singleton
├── LoggerConfig.php              # Configuration value object
├── LoggerServiceProvider.php    # Laravel service provider
├── LoggerFacade.php             # Laravel facade
├── Formatter/
│   └── StructuredJsonFormatter.php  # Custom Monolog formatter
├── Middleware/
│   └── HttpLoggerMiddleware.php     # HTTP logging middleware
└── Processor/
    └── TraceContextProcessor.php    # OpenTelemetry processor

tests/
├── TestCase.php                 # Base test class
├── LoggerTest.php               # Logger tests
└── LoggerConfigTest.php         # Config tests
```

## Coding Standards

This project follows PSR-12 coding standards.

### Code Style

```php
<?php

namespace Rcommerz\Logger;

class ExampleClass
{
    private string $property;

    public function exampleMethod(string $param): void
    {
        // Method body
    }
}
```

### Naming Conventions

- **Classes**: PascalCase (e.g., `StructuredJsonFormatter`)
- **Methods**: camelCase (e.g., `getInstance()`)
- **Variables**: camelCase (e.g., `$serviceName`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `DEFAULT_LEVEL`)

### Documentation

- Add PHPDoc blocks for classes and public methods
- Include `@param` and `@return` annotations
- Explain complex logic with inline comments

```php
/**
 * Process exception and extract error details.
 *
 * @param \Throwable $exception The exception to process
 * @return array<string, mixed> Extracted error details
 */
private function processException(\Throwable $exception): array
{
    // Implementation
}
```

## Testing

### Writing Tests

- Test one behavior per test method
- Use descriptive test names
- Follow Arrange-Act-Assert pattern
- Use data providers for multiple scenarios

```php
public function test_info_logs_message_with_context(): void
{
    // Arrange
    $logger = Logger::getInstance();
    
    // Act
    $logger->info('Test message', ['key' => 'value']);
    
    // Assert
    // Add assertions
}
```

### Running Tests

```bash
# All tests
vendor/bin/phpunit

# Specific test file
vendor/bin/phpunit tests/LoggerTest.php

# Specific test method
vendor/bin/phpunit --filter test_info_logs_message_with_context

# With coverage
vendor/bin/phpunit --coverage-html coverage
```

## Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**

```
feat(middleware): add support for custom header filtering

Added ability to configure custom sensitive headers
that should be redacted from HTTP logs.

Closes #123
```

```
fix(formatter): handle null datetime values

Previously, null datetime values would cause a fatal error.
Now they are properly handled and logged as 'null'.
```

## Versioning

This project uses [Semantic Versioning](https://semver.org/):

- **MAJOR**: Breaking changes
- **MINOR**: New features (backward compatible)
- **PATCH**: Bug fixes (backward compatible)

## Release Process

1. Update CHANGELOG.md with release notes
2. Update version in relevant files
3. Create git tag: `git tag v1.x.x`
4. Push tag: `git push origin v1.x.x`
5. Create GitHub release with notes
6. Package is automatically published to Packagist

## Questions?

- Open a [GitHub Discussion](https://github.com/rcommerz/logger-laravel/discussions)
- Email: <dev@rcommerz.com>

Thank you for your contributions! 🎉
