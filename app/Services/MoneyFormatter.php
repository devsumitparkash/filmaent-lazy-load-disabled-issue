<?php

namespace App\Services;

use App\Enums\Currency as EnumsCurrency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class MoneyFormatter
{
    private DecimalMoneyFormatter $decimalFormatter;

    private IntlMoneyFormatter $currencyFormatter;

    public function __construct()
    {
        $currencies = new ISOCurrencies;

        // Initialize decimal formatter
        $this->decimalFormatter = new DecimalMoneyFormatter($currencies);

        // Initialize currency formatter
        $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $numberFormatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 0);
        $numberFormatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $this->currencyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);
    }

    public function formatDecimal(Money $money): string
    {
        $amount = $this->decimalFormatter->format($money);

        return rtrim(rtrim($amount, '0'), '.');
    }

    public function getMoneyObject(int $amount, string $currencyCode): Money
    {
        return new Money($amount, new \Money\Currency($currencyCode));
    }

    public function formatWithCurrency(Money $money): string
    {
        return $this->currencyFormatter->format($money);
    }

    public function getCurrencyCode(Money $money): string
    {
        return $money->getCurrency()->getCode();
    }

    public function getCurrencySymbol(Money $money): string
    {
        return EnumsCurrency::fromMoneyPHPCurrency($money->getCurrency()->getCode())?->getSymbol();
    }
}
