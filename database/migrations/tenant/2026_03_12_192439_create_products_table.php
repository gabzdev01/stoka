<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('category')->nullable();
            $table->enum('type', ['unit', 'measured', 'variant'])->default('unit');
            $table->decimal('shelf_price', 10, 2);
            $table->decimal('floor_price', 10, 2)->nullable();
            $table->boolean('is_bargainable')->default(false);
            $table->boolean('track_stock')->default(true);
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(3);
            $table->boolean('low_stock_alert_sent')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};