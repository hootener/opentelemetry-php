<?php

declare(strict_types=1);

namespace OpenTelemetry\Sdk\Trace\Sampler;

use OpenTelemetry\Context\Context;
use OpenTelemetry\Sdk\Trace\Baggage;
use OpenTelemetry\Sdk\Trace\Sampler;
use OpenTelemetry\Sdk\Trace\SamplingResult;
use OpenTelemetry\Sdk\Trace\Span;
use OpenTelemetry\Trace as API;

/**
 * This implementation of the SamplerInterface always skips record.
 * Example:
 * ```
 * use OpenTelemetry\Sdk\Trace\AlwaysOffSampler;
 * $sampler = new AlwaysOffSampler();
 * ```
 */
class AlwaysOffSampler implements Sampler
{
    /**
     * Returns false because we never want to sample.
     * {@inheritdoc}
     */
    public function shouldSample(
        Context $parentContext,
        string $traceId,
        string $spanName,
        int $spanKind,
        ?API\Attributes $attributes = null,
        ?API\Links $links = null
    ): SamplingResult {
        $parentSpan = Span::extract($parentContext);
        $parentBaggage = $parentSpan !== null ? $parentSpan->getContext() : Baggage::getInvalid();
        $traceState = $parentBaggage->getTraceState();

        return new SamplingResult(
            SamplingResult::DROP,
            null,
            $traceState
        );
    }

    public function getDescription(): string
    {
        return 'AlwaysOffSampler';
    }
}
