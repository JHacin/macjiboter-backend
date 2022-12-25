<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSponsorshipMessageTypesTable extends Migration
{
    public function up()
    {
        Schema::create('sponsorship_message_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('subject');
            $table->string('template_id')->unique();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sponsorship_message_types');
    }
}
