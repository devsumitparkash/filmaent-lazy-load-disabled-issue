<?php

namespace App\Models\Business\Order;

use App\Casts\MoneyDecimalCast;
use App\Models\Traits\HasMonetaryAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

class OrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\Business\Order\OrderItemFactory> */
    use HasFactory;

    use HasMonetaryAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'item_name',
        'quantity',
        'price',
        'tax',
        'total',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'total' => MoneyDecimalCast::class,
    ];

    protected function priceAsMoneyObject(): Attribute
    {
        $column = 'price';

        return Attribute::make(
            get: function (mixed $value, array $attributes) use ($column): Money {
                return $this->monetaryAsMoneyObject($column, $this->order->currency_code);
            },
        );
    }

    public function formattedPriceWithCurrency(): Attribute
    {
        $column = 'price';

        return Attribute::make(
            get: function (mixed $value) use ($column): string {
                return $this->formattedMonetaryWithCurrency($column);
            },
        );
    }

    protected function totalAsMoneyObject(): Attribute
    {
        $column = 'total';

        return Attribute::make(
            get: function (mixed $value, array $attributes) use ($column): Money {
                return $this->monetaryAsMoneyObject($column, $this->order->currency_code);
            },
        );
    }

    public function formattedTotalWithCurrency(): Attribute
    {
        $column = 'total';

        return Attribute::make(
            get: function (mixed $value) use ($column): string {
                return $this->formattedMonetaryWithCurrency($column);
            },
        );
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
