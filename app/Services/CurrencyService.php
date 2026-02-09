<?php

namespace App\Services;

class CurrencyService
{
    const RATES = [
        'usd' => [
            'eur' => 0.85,
        ]
    ];

    public function convert(float $amount, string $currencyForm, string $currencyTo): float
    {
        $rare = self::RATES[$currencyForm][$currencyTo] ?? 0;

        return round($amount * $rare, 2);
    }
}

