<?php

namespace App\Enums;

enum Currency: string
{
    case USD = 'usd';

    public function getSymbol(): string
    {
        return match ($this) {
            self::USD => '$',
        };
    }

    public static function getDefaultCurrency(): self
    {
        return self::USD;
    }

    public function getMoneyPHPCurrency(): string
    {
        return match ($this) {
            self::USD => 'USD',
        };
    }

    public static function fromMoneyPHPCurrency(string $currencyCode): ?self
    {
        return match ($currencyCode) {
            'USD' => self::USD,
            default => null,
        };
    }
}
