<?php

namespace App\Models\Traits;

use Money\Currency;
use Money\Money;

trait HasMonetaryAttributes
{
    protected function monetaryAsMoneyObject(string $column, ?string $currencyCode = null): Money
    {
        $currencyCode = $currencyCode ?? getDefaultMoneyPHPCurrency();
        $currency = new Currency($currencyCode);

        return getMoneyFormatterService()->getMoneyObject(
            ($this->{$column} * 100),
            $currency
        );
    }

    protected function monetaryAsFormattedDecimal(string $column): string
    {
        return getMoneyFormatterService()->formatDecimal($this->{$column.'_as_money_object'});
    }

    protected function formattedMonetaryWithCurrency(string $column): string
    {
        return getMoneyFormatterService()->formatWithCurrency($this->{$column.'_as_money_object'});
    }

    protected function monetaryCurrencyCode(string $column): string
    {
        return getMoneyFormatterService()->getCurrencyCode($this->{$column.'_as_money_object'});
    }

    protected function monetaryCurrencySymbol(string $column): string
    {
        return getMoneyFormatterService()->getCurrencySymbol($this->{$column.'_as_money_object'});
    }
}
