<?php

namespace App\Models\Business\Order;

use App\Casts\MoneyDecimalCast;
use App\Enums\OrderStatus;
use App\Events\Order\OrderPaid;
use App\Models\Scopes\Local\HasCurrentTeamScope;
use App\Models\Traits\HasMonetaryAttributes;
use App\Models\Traits\TeamRelationshipHandler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Money\Money;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\Business\Order\OrderFactory> */
    use HasFactory;

    use HasMonetaryAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_number',
        'customer_id',
        'sub_total',
        'tax',
        'shipping_cost',
        'platform_fee',
        'total',
        'currency_code',
        'payment_method_code',
        'payment_method_title',
        'shipping_method',
        'shipping_method_title',
        'status',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => OrderStatus::class,
        'total' => MoneyDecimalCast::class,
    ];


    protected function subTotalAsMoneyObject(): Attribute
    {
        $column = 'sub_total';

        return Attribute::make(
            get: function (mixed $value, array $attributes) use ($column): Money {
                return $this->monetaryAsMoneyObject($column, $this->attributes['currency_code']);
            },
        );
    }

    public function formattedSubTotalWithCurrency(): Attribute
    {
        $column = 'sub_total';

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
                return $this->monetaryAsMoneyObject($column, $this->attributes['currency_code']);
            },
        );
    }

    public function totalAsFormattedDecimal(): Attribute
    {
        $column = 'total';

        return Attribute::make(
            get: function (mixed $value) use ($column): string {
                return $this->monetaryAsFormattedDecimal($column);
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

    public function currencyCode(): Attribute
    {
        $column = 'total';

        return Attribute::make(
            get: function (mixed $value, array $attributes) use ($column) {
                return $this->monetaryCurrencyCode($column);
            },
        );
    }

    public function currencySymbol(): Attribute
    {
        $column = 'total';

        return Attribute::make(
            get: function (mixed $value, array $attributes) use ($column) {
                return $this->monetaryCurrencySymbol($column);
            },
        );
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid;
    }

    public function markAsPaid(): void
    {
        if ($this->isPaid()) {
            return;
        }

        $this->status = OrderStatus::Paid;
        $this->save();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)
        // ->chaperone()
        ;
    }

    public function totals(): HasMany
    {
        return $this->hasMany(OrderTotal::class)
        // ->chaperone()
        ;
    }
}
