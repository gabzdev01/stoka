<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierBalance extends Model
{
    protected $fillable = [
        'supplier_id', 'restock_id', 'total_cost', 'amount_paid', 'balance', 'settled_at',
    ];

    protected $casts = [
        'total_cost'  => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance'     => 'decimal:2',
        'settled_at'  => 'datetime',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function restock(): BelongsTo
    {
        return $this->belongsTo(Restock::class);
    }
}
