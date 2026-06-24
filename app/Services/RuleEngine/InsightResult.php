<?php

namespace App\Services\RuleEngine;

/**
 * Value Object yang merepresentasikan hasil evaluasi sebuah rule.
 * Immutable — dibuat sekali dan tidak berubah.
 */
class InsightResult
{
    public function __construct(
        public readonly string $alertLevel,       // 'low' | 'medium' | 'high'
        public readonly string $message,
        public readonly array  $recommendations,
        public readonly string $title   = '',
        public readonly string $insightType = '',
    ) {}

    public static function high(string $message, array $recommendations, string $title = '', string $insightType = ''): self
    {
        return new self('high', $message, $recommendations, $title, $insightType);
    }

    public static function medium(string $message, array $recommendations, string $title = '', string $insightType = ''): self
    {
        return new self('medium', $message, $recommendations, $title, $insightType);
    }

    public static function low(string $message, array $recommendations, string $title = '', string $insightType = ''): self
    {
        return new self('low', $message, $recommendations, $title, $insightType);
    }
}
