<?php

namespace Rcommerz\Logger;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void info(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warn(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void security(string $message, array $context = [])
 * @method static void audit(string $message, array $context = [])
 * @method static void http(string $message, array $context = [])
 *
 * @see Logger
 */
class LoggerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'rcommerz.logger';
    }
}
