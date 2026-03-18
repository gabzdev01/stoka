<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = ['name', 'phone', 'category', 'notes'];

    public function balances(): HasMany
    {
        return $this->hasMany(SupplierBalance::class);
    }
}
