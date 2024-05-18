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
        Schema::create('restaurantlocations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Restaurant_id')->unsigned();
            $table->foreign('Restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->string('latitude')->default('0');
			      $table->string('longitude')->default('0');
            $table->string('state')->default('0');
            $table->string('text')->default('0');
            $table->string('ar_text')->default('0');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurantlocations');
    }
};
