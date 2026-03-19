<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditLedger extends Model
{
    protected $table = 'credit_ledger';

    protected $fillable = [
        'customer_id',
        'sale_id',
        'amount',
        'paid',
        'balance',
        'last_payment_at',
        'status',
        'whatsapp_failed',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'paid'             => 'decimal:2',
        'balance'          => 'decimal:2',
        'last_payment_at'  => 'datetime',
        'whatsapp_failed'  => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
