<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('person_data', function (Blueprint $table) {
            $table->boolean('is_gender_exception')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('person_data', function (Blueprint $table) {
            $table->dropColumn('is_gender_exception');
        });
    }
};
