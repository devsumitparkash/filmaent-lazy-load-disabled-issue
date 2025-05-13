<?php

namespace App\Models\Business\Order;

use App\Casts\MoneyDecimalCast;
use App\Models\Traits\HasMonetaryAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

class OrderTotal extends Model
{
    /** @use HasFactory<\Database\Factories\Business\Order\OrderTotalFactory> */
    use HasFactory;

    use HasMonetaryAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'code',
        'title',
        'value',
        'sort_order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => MoneyDecimalCast::class,
    ];

    protected function valueAsMoneyObject(): Attribute
    {
        $column = 'value';

        return Attribute::make(
            get: function (mixed $value, array $attributes) use ($column): Money {
                return $this->monetaryAsMoneyObject($column, $this->order->currency_code);
            },
        );
    }

    public function formattedValueWithCurrency(): Attribute
    {
        $column = 'value';

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
