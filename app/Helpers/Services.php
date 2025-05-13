<?php

use App\Services\GeocoderService;
use App\Services\MoneyFormatter;
use Laravel\Cashier\Cashier;
use Money\Currency;

function getMoneyFormatterService(): MoneyFormatter
{
    return app(MoneyFormatter::class);
}

function formatPrice(float $price, ?string $currencyCode = null): string
{
    $currencyCode = $currencyCode ?? getDefaultMoneyPHPCurrency();
    $money = getMoneyFormatterService()->getMoneyObject($price * 100, new Currency($currencyCode));

    return getMoneyFormatterService()->formatDecimal($money);
}
