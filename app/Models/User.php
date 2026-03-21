<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $fillable = [
        "name",
        "phone",
        "role",
        "pin",
        "password",
        "active",
        "dashboard_last_seen",
    ];

    protected $hidden = [
        "password",
        "pin",
    ];

    protected function casts(): array
    {
        return [
            "active"               => "boolean",
            "dashboard_last_seen"  => "datetime",
        ];
    }

    public function isOwner(): bool
    {
        return $this->role === "owner";
    }

    public function isStaff(): bool
    {
        return $this->role === "staff";
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(Shift::class, 'staff_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'staff_id');
    }
}
