<?php

use App\Models\Sponsorship;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSponsorshipsTable extends Migration
{
    public function up()
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cat_id')->nullable()->constrained('cats')->nullOnDelete();
            $table->foreignId('sponsor_id')->nullable()->constrained('person_data')->nullOnDelete();
            $table->foreignId('payer_id')->nullable()->constrained('person_data')->nullOnDelete();
            $table->boolean('is_gift')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->smallInteger('payment_type')->default(Sponsorship::PAYMENT_TYPE_BANK_TRANSFER);
            $table->decimal('monthly_amount');
            $table->integer('requested_duration')->nullable();
            $table->boolean('is_active')->default(false);
            $table->date('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sponsorships');
    }
}
