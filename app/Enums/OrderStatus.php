<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: int implements HasColor, HasIcon, HasLabel
{
    case Pending = 0;       // Order created, awaiting payment
    case Paid = 1;          // Payment successful
    case Failed = 2;        // Payment failed (optional)
    case Processing = 3;    // Restaurant is preparing the order
    case Ready = 4;         // Order ready for pickup (optional)
    case Delivered = 5;     // Order handed to customer
    case Cancelled = 6;     // Order was cancelled

    public static function getDefaultStatus(): self
    {
        return self::Pending;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Paid => 'Paid',
            self::Failed => 'Payment Failed',
            self::Processing => 'Processing',
            self::Ready => 'Ready for Pickup',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Paid => 'success',
            self::Failed => 'danger',
            self::Processing => 'warning',
            self::Ready => 'info',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getAppColor(): string
    {
        return match ($this) {
            self::Pending => '#71717A',
            self::Paid => '#22C55E',
            self::Failed => '#EF4444',
            self::Processing => '#f59e0b',
            self::Ready => '#3B82F6',
            self::Delivered => '#22C55E',
            self::Cancelled => '#EF4444',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Paid => 'heroicon-o-currency-dollar',
            self::Failed => 'heroicon-o-x-circle',
            self::Processing => 'heroicon-o-adjustments-horizontal',
            self::Ready => 'heroicon-o-check-circle',
            self::Delivered => 'heroicon-o-truck',
            self::Cancelled => 'heroicon-o-ban',
        };
    }
}
