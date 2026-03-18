<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Restock extends Model
{
    protected $fillable = ['supplier_id', 'staff_id', 'notes'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RestockItem::class);
    }

    public function supplierBalance(): HasOne
    {
        return $this->hasOne(SupplierBalance::class);
    }
}
