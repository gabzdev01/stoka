<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        "name",
        "phone",
        "role",
        "pin",
        "password",
        "active",
    ];

    protected $hidden = [
        "password",
        "pin",
    ];

    protected function casts(): array
    {
        return [
            "active" => "boolean",
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
}
