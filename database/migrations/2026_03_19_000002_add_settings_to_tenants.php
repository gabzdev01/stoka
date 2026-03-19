<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->integer('default_low_stock_threshold')->default(3)->after('plan');
            $table->string('currency')->default('KES')->after('default_low_stock_threshold');
            $table->string('operating_hours_open', 10)->nullable()->after('currency');
            $table->string('operating_hours_close', 10)->nullable()->after('operating_hours_open');
            $table->string('shop_location')->nullable()->after('operating_hours_close');
            $table->text('shop_description')->nullable()->after('shop_location');
            $table->boolean('receipt_digital')->default(true)->after('shop_description');
            $table->boolean('receipt_print')->default(false)->after('receipt_digital');
            $table->string('receipt_footer')->nullable()->after('receipt_print');
            $table->boolean('notify_shift_close')->default(true)->after('receipt_footer');
            $table->boolean('notify_low_stock')->default(true)->after('notify_shift_close');
            $table->boolean('notify_credit_overdue')->default(true)->after('notify_low_stock');
            $table->string('password_reset_token')->nullable()->after('notify_credit_overdue');
            $table->timestamp('password_reset_expires_at')->nullable()->after('password_reset_token');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
