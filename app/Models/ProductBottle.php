<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductBottle extends Model
{
    protected $fillable = ['product_id', 'total_ml', 'remaining_ml', 'price_per_ml', 'active'];

    protected $casts = [
        'total_ml'     => 'decimal:2',
        'remaining_ml' => 'decimal:2',
        'price_per_ml' => 'decimal:2',
        'active'       => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
