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
        Schema::create('restaurantoperatinghours', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('Restaurant_id')->unsigned();
            $table->foreign('Restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');

            $table->string('date_start')->nullable();
            $table->string('date_end')->nullable();

            $table->string('sat_from')->nullable();
            $table->string('sat_to')->nullable();

            $table->string('sun_from')->nullable();
            $table->string('sun_to')->nullable();

            $table->string('mon_from')->nullable();
            $table->string('mon_to')->nullable();

            $table->string('tue_from')->nullable();
            $table->string('tue_to')->nullable();

            $table->string('wed_from')->nullable();
            $table->string('wed_to')->nullable();

            $table->string('thu_from')->nullable();
            $table->string('thu_to')->nullable();

            $table->string('fri_from')->nullable();
            $table->string('fri_to')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurantoperatinghours');
    }
};
