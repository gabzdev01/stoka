<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'owner_name',
            'owner_phone',
            'owner_whatsapp',
            'plan',
            'status',
            'default_low_stock_threshold',
            'currency',
            'operating_hours_open',
            'operating_hours_close',
            'shop_location',
            'shop_description',
            'receipt_digital',
            'receipt_print',
            'receipt_footer',
            'notify_shift_close',
            'notify_low_stock',
            'notify_credit_overdue',
            'password_reset_token',
            'password_reset_expires_at',
        ];
    }

    public function getCurrencySymbolAttribute(): string
    {
        return match($this->currency ?? 'KES') {
            'KES' => 'Ksh',
            'UGX' => 'USh',
            'TZS' => 'TSh',
            'RWF' => 'RWF',
            'ETB' => 'ETB',
            default => 'Ksh',
        };
    }
}
