<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('db_migration_meta', function (Blueprint $table) {
            $table->id();
            $table->string("entity");
            $table->unsignedBigInteger("new_id");
            $table->unsignedBigInteger("prev_id");
            $table->json("prev_data");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('db_migration_meta');
    }
};
