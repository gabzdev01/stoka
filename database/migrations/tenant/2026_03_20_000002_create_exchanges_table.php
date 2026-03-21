<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained();
            $table->foreignId('staff_id')->constrained('users');
            $table->foreignId('returned_sale_id')->constrained('sales');
            $table->foreignId('new_sale_id')->constrained('sales');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->decimal('returned_value', 10, 2);
            $table->decimal('new_value', 10, 2);
            $table->decimal('difference', 10, 2)->default(0);
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
