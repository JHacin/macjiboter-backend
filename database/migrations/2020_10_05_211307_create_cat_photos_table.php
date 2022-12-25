<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCatPhotosTable extends Migration
{
    public function up()
    {
        Schema::create('cat_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('filename');
            $table->string('caption')->nullable();
            $table->integer('index');
            $table->json('sizes');
            $table->foreignId('cat_id')->constrained('cats')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cat_photos');
    }
}
