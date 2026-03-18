<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'staff_id',
        'opened_at',
        'closed_at',
        'opening_float',
        'cash_counted',
        'mpesa_total',
        'expected_cash',
        'cash_discrepancy',
        'status',
        'wa_sent_at',
    ];

    protected $casts = [
        'opened_at'        => 'datetime',
        'closed_at'        => 'datetime',
        'wa_sent_at'       => 'datetime',
        'opening_float'    => 'decimal:2',
        'cash_counted'     => 'decimal:2',
        'mpesa_total'      => 'decimal:2',
        'expected_cash'    => 'decimal:2',
        'cash_discrepancy' => 'decimal:2',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function activeSales(): HasMany
    {
        return $this->hasMany(Sale::class)->whereNull('voided_at');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /** Running cash total for this shift (active sales only). */
    public function cashTotal(): string
    {
        return $this->activeSales()->where('payment_type', 'cash')->sum('total');
    }

    /** Running M-Pesa total for this shift (active sales only). */
    public function mpesaRunningTotal(): string
    {
        return $this->activeSales()->where('payment_type', 'mpesa')->sum('total');
    }
}
