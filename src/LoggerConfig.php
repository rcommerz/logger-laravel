<?php

namespace Rcommerz\Logger;

class LoggerConfig
{
    public function __construct(
        public string $serviceName,
        public string $serviceVersion,
        public string $env,
        public string $level = 'INFO'
    ) {
    }

    public static function fromEnv(): self
    {
        return new self(
            serviceName: config('rcommerz_logger.service_name', env('SERVICE_NAME', config('app.name', 'laravel-app'))),
            serviceVersion: config('rcommerz_logger.service_version', env('SERVICE_VERSION', '1.0.0')),
            env: config('rcommerz_logger.env', env('APP_ENV', 'production')),
            level: config('rcommerz_logger.level', env('LOG_LEVEL', 'INFO'))
        );
    }
}
