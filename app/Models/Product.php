<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'supplier_id', 'name', 'category', 'type',
        'shelf_price', 'floor_price', 'is_bargainable',
        'track_stock', 'stock', 'low_stock_threshold',
        'low_stock_alert_sent', 'status',
    ];

    protected $casts = [
        'shelf_price'          => 'decimal:2',
        'floor_price'          => 'decimal:2',
        'is_bargainable'       => 'boolean',
        'track_stock'          => 'boolean',
        'low_stock_alert_sent' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function bottles(): HasMany
    {
        return $this->hasMany(ProductBottle::class);
    }
}
