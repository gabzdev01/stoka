<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    protected $fillable = [
        'shift_id',
        'staff_id',
        'product_id',
        'variant_id',
        'bottle_id',
        'customer_id',
        'quantity_or_ml',
        'unit_price',
        'actual_price',
        'total',
        'payment_type',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'quantity_or_ml' => 'decimal:2',
        'unit_price'     => 'decimal:2',
        'actual_price'   => 'decimal:2',
        'total'          => 'decimal:2',
        'voided_at'      => 'datetime',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function bottle(): BelongsTo
    {
        return $this->belongsTo(ProductBottle::class, 'bottle_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creditLedger(): HasOne
    {
        return $this->hasOne(CreditLedger::class);
    }

    public function isVoided(): bool
    {
        return $this->voided_at !== null;
    }
}
