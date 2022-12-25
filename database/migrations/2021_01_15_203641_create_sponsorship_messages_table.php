<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSponsorshipMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsorship_messages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table
                ->foreignId('message_type_id')
                ->nullable()
                ->constrained('sponsorship_message_types')
                ->nullOnDelete();

            $table
                ->foreignId('cat_id')
                ->nullable()
                ->constrained('cats')
                ->nullOnDelete();

            $table
                ->foreignId('sponsor_id')
                ->nullable()
                ->constrained('person_data')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sponsorship_messages');
    }
}
