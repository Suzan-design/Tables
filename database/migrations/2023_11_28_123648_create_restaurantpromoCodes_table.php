<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurantpromoCodes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->bigInteger('promocode_id')->unsigned();
            $table->foreign('promocode_id')->references('id')->on('promotionalcodes')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->timestamps();
     });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurantpromoCodes');
    }
};
