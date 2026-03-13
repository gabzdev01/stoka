<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    public function up(): void
    {
        Schema::create("tenants", function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string("name");
            $table->string("owner_name");
            $table->string("owner_phone");
            $table->string("owner_whatsapp")->nullable();
            $table->string("plan")->default("basic");
            $table->string("status")->default("active");
            $table->json("data")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("tenants");
    }
}
