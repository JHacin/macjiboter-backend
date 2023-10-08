<?php

use App\Models\Cat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatsTable extends Migration
{
    public function up()
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->smallInteger('gender')->nullable();
            $table->smallInteger('status')->default(Cat::STATUS_NOT_SEEKING_SPONSORS);
            $table->text('story_short');
            $table->text('story')->nullable();
            $table->date('date_of_arrival_mh')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('is_group')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->string('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cats');
    }
}
