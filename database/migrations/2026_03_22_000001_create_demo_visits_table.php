<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demo_visits', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->enum('role', ['owner', 'staff'])->default('owner');
            $table->string('ref')->nullable(); // which article/page referred them
            $table->string('ip')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_visits');
    }
};
