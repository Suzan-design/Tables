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
        Schema::create('tables', function (Blueprint $table) {
        $table->id();
        $table->string('number');
        $table->bigInteger('Restaurant_id')->unsigned();
        $table->foreign('Restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        $table->integer('capacity');
        $table->string('size');
        $table->string('location');
        $table->string('type');
        $table->timestamps();
    
        // Add a composite unique index on number and Restaurant_id
        $table->unique(['number', 'Restaurant_id']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
