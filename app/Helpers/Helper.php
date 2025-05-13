<?php


use App\Enums\Currency;


function getDefaultCurrency(): Currency
{
    return Currency::getDefaultCurrency();
}
function getDefaultMoneyPHPCurrency(): string
{
    return getDefaultCurrency()->getMoneyPHPCurrency();
}


require 'Services.php';
