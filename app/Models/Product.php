<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'supplier_id', 'name', 'category', 'gender', 'type',
        'shelf_price', 'floor_price', 'is_bargainable',
        'track_stock', 'stock', 'low_stock_threshold',
        'low_stock_alert_sent', 'status',
        'photo', 'description', 'shop_visible', 'slug',
    ];

    protected $casts = [
        'shelf_price'          => 'decimal:2',
        'floor_price'          => 'decimal:2',
        'is_bargainable'       => 'boolean',
        'track_stock'          => 'boolean',
        'low_stock_alert_sent' => 'boolean',
        'shop_visible'         => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (empty($product->slug)) {
                $product->slug = static::uniqueSlug($product->name);
            }
        });
    }

    public static function uniqueSlug(string $name, int $excludeId = 0): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        while (static::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

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

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /** Total stock across all variants, or direct stock for unit products */
    public function getTotalStockAttribute(): int
    {
        if ($this->type === 'variant') {
            return $this->variants->sum('stock');
        }
        return (int) $this->stock;
    }

    /** Returns array of in-stock variant sizes */
    public function getAvailableSizesAttribute(): array
    {
        if ($this->type !== 'variant') return [];
        return $this->variants
            ->where('stock', '>', 0)
            ->pluck('size')
            ->filter()
            ->values()
            ->toArray();
    }

    /** Returns array of all variant sizes with stock info */
    public function getAllSizesAttribute(): array
    {
        if ($this->type !== 'variant') return [];
        return $this->variants->map(fn($v) => [
            'size'  => $v->size,
            'stock' => $v->stock,
        ])->toArray();
    }
}
