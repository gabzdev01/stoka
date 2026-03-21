<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('status');
            $table->string('description', 200)->nullable()->after('photo');
            $table->boolean('shop_visible')->default(false)->after('description');
        });
    }
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['photo', 'description', 'shop_visible']);
        });
    }
};
