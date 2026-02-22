<?php

namespace Rcommerz\Logger\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use OpenTelemetry\API\Trace\Span;

/**
 * Processor to add OpenTelemetry trace context to log records
 */
class TraceContextProcessor implements ProcessorInterface
{
    /**
     * Add trace context to the log record
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $traceContext = $this->getTraceContext();

        if (!empty($traceContext)) {
            $record = $record->with(extra: array_merge($record->extra, $traceContext));
        }

        return $record;
    }

    /**
     * Extract OpenTelemetry trace context
     */
    private function getTraceContext(): array
    {
        try {
            $span = Span::getCurrent();
            if ($span && $span->getContext()->isValid()) {
                $context = $span->getContext();
                return [
                    'trace_id' => $context->getTraceId(),
                    'span_id' => $context->getSpanId(),
                    'trace_flags' => $context->getTraceFlags(),
                ];
            }
        } catch (\Throwable) {
            // OpenTelemetry not configured or unavailable
        }

        return [];
    }
}
