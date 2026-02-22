<?php

namespace Rcommerz\Logger\Formatter;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;

/**
 * Custom JSON formatter for structured logging
 * Formats logs in ECS (Elastic Common Schema) compatible format
 */
class StructuredJsonFormatter extends NormalizerFormatter
{
    private string $serviceName;
    private string $serviceVersion;
    private string $environment;
    private string $hostname;

    public function __construct(
        string $serviceName,
        string $serviceVersion,
        string $environment
    ) {
        parent::__construct('Y-m-d\TH:i:s.v\Z');
        $this->serviceName = $serviceName;
        $this->serviceVersion = $serviceVersion;
        $this->environment = $environment;
        $this->hostname = gethostname() ?: 'unknown';
    }

    /**
     * Format a log record into structured JSON
     */
    public function format(LogRecord $record): string
    {
        $formatted = [
            '@timestamp' => $record->datetime->format($this->dateFormat),
            'log.level' => $record->level->getName(),
            'service.name' => $this->serviceName,
            'service.version' => $this->serviceVersion,
            'env' => $this->environment,
            'host.name' => $this->hostname,
            'message' => $record->message,
        ];

        // Merge context data
        if (!empty($record->context)) {
            $formatted = array_merge($formatted, $this->normalize($record->context));
        }

        // Merge extra data
        if (!empty($record->extra)) {
            $formatted = array_merge($formatted, $this->normalize($record->extra));
        }

        return $this->toJson($formatted) . "\n";
    }

    /**
     * Format multiple records
     */
    public function formatBatch(array $records): string
    {
        $formatted = '';
        foreach ($records as $record) {
            $formatted .= $this->format($record);
        }
        return $formatted;
    }

    /**
     * Convert array to JSON
     */
    protected function toJson($data, bool $ignoreErrors = false): string
    {
        return json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION
        );
    }
}
