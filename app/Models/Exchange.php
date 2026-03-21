<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exchange extends Model
{
    protected $fillable = [
        'shift_id', 'staff_id', 'returned_sale_id', 'new_sale_id',
        'customer_id', 'returned_value', 'new_value', 'difference', 'notes',
    ];

    public function shift(): BelongsTo      { return $this->belongsTo(Shift::class); }
    public function staff(): BelongsTo      { return $this->belongsTo(User::class, 'staff_id'); }
    public function returnedSale(): BelongsTo { return $this->belongsTo(Sale::class, 'returned_sale_id'); }
    public function newSale(): BelongsTo    { return $this->belongsTo(Sale::class, 'new_sale_id'); }
    public function customer(): BelongsTo   { return $this->belongsTo(Customer::class); }
}
