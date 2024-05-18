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
          // images - price_old -   price_new - desc - name - featured - res_id
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Restaurant_id')->unsigned();
            $table->foreign('Restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->string('price_old')->nullable();
            $table->string('price_new')->nullable();
            $table->string('description');
            $table->string('name');
            $table->string('ar_description');
            $table->string('ar_name');
            $table->string('type'); // offer -  new_opening
            $table->string('start_date')->nullable();
            $table->string('status')->default('active');
            $table->json('featured')->nullable();
            $table->json('ar_featured')->nullable();
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
