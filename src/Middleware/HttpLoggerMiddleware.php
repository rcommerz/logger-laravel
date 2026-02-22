<?php

namespace Rcommerz\Logger\Middleware;

use Closure;
use Illuminate\Http\Request;
use Rcommerz\Logger\Logger;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware for logging HTTP requests and responses
 */
class HttpLoggerMiddleware
{
    private Logger $logger;
    private array $excludePaths;
    private bool $includeHeaders;
    private bool $includeBody;

    public function __construct()
    {
        $this->logger = Logger::getInstance();
        $this->excludePaths = config('rcommerz_logger.exclude_paths', ['/health', '/metrics']);
        $this->includeHeaders = config('rcommerz_logger.include_headers', false);
        $this->includeBody = config('rcommerz_logger.include_body', false);
    }

    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip excluded paths
        if ($this->shouldSkipLogging($request)) {
            return $next($request);
        }

        $startTime = microtime(true);

        // Process request
        $response = $next($request);

        // Log the request/response
        $this->logRequest($request, $response, $startTime);

        return $response;
    }

    /**
     * Check if request should be skipped
     */
    private function shouldSkipLogging(Request $request): bool
    {
        foreach ($this->excludePaths as $path) {
            if ($request->is(trim($path, '/'))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Log the HTTP request
     */
    private function logRequest(Request $request, Response $response, float $startTime): void
    {
        $durationMs = round((microtime(true) - $startTime) * 1000, 2);
        $statusCode = $response->getStatusCode();

        $context = $this->buildContext($request, $response, $durationMs);
        $message = $this->buildMessage($request, $statusCode);

        // Log based on status code
        match (true) {
            $statusCode >= 500 => $this->logger->error($message, $context),
            $statusCode >= 400 => $this->logger->warn($message, $context),
            default => $this->logger->http($message, $context),
        };
    }

    /**
     * Build log context
     */
    private function buildContext(Request $request, Response $response, float $duration): array
    {
        $context = [
            'http.method' => $request->method(),
            'http.path' => $request->path(),
            'http.status_code' => $response->getStatusCode(),
            'http.duration_ms' => $duration,
            'client.ip' => $request->ip(),
            'http.user_agent' => $request->userAgent(),
        ];

        // Add query parameters
        if ($query = $request->query()) {
            $context['http.query'] = $query;
        }

        // Add headers if enabled
        if ($this->includeHeaders) {
            $context['http.headers'] = $this->filterHeaders($request->headers->all());
        }

        // Add body if enabled
        if ($this->includeBody && $request->getContent()) {
            $context['http.body'] = $this->getRequestBody($request);
        }

        // Add authenticated user
        if ($user = $request->user()) {
            $context['user.id'] = $user->id ?? $user->getAuthIdentifier();
        }

        return $context;
    }

    /**
     * Build log message
     */
    private function buildMessage(Request $request, int $statusCode): string
    {
        return sprintf(
            'HTTP %s %s - %d',
            $request->method(),
            $request->path(),
            $statusCode
        );
    }

    /**
     * Get request body safely
     */
    private function getRequestBody(Request $request): array
    {
        try {
            return $request->all();
        } catch (\Throwable) {
            return ['_error' => 'Could not parse request body'];
        }
    }

    /**
     * Filter sensitive headers
     */
    private function filterHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'cookie', 'x-api-key', 'x-auth-token'];

        foreach ($sensitiveHeaders as $header) {
            if (isset($headers[$header])) {
                $headers[$header] = ['***REDACTED***'];
            }
        }

        return $headers;
    }
}
