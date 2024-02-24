<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSpecialSponsorshipsTable extends Migration
{
    public function up()
    {
        Schema::create('special_sponsorships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('type');
            $table->foreignId('sponsor_id')->nullable()->constrained('person_data')->nullOnDelete();
            $table->foreignId('payer_id')->nullable()->constrained('person_data')->nullOnDelete();
            $table->boolean('is_gift')->default(false);
            $table->date('confirmed_at')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->decimal('amount', 10);
            $table->text('gift_message')->nullable();
            $table->text('gift_notes')->nullable();
            $table->date('gift_requested_activation_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('special_sponsorships');
    }
}
