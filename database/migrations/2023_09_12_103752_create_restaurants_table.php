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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('cuisine_id')->unsigned()->nullable();
            $table->foreign('cuisine_id')->references('id')->on('cuisines')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('restaurnats_categories')->onDelete('cascade');
            $table->text('time_start')->nullable();
            $table->text('time_end')->nullable();
            $table->date('Activation_start');
            $table->date('Activation_end');
            $table->text('name');
            $table->text('description');
            $table->text('ar_description');
            $table->text('age_range')->nullable();
            $table->string('phone_number');
            $table->string('website')->default('www.Restaurant.com');
            $table->string('instagram')->default('@insta.com');
            $table->double('Deposite_value');
            $table->double('taxes');
            $table->string('Deposite_desc');
            $table->string('refund_policy');
            $table->string('change_policy');
            $table->string('cancellition_policy');
            $table->string('ar_Deposite_desc');
            $table->string('ar_refund_policy');
            $table->string('ar_change_policy');
            $table->string('ar_cancellition_policy');
            $table->string('availability')->default('available');
            $table->string('ar_availability')->default('متوفر');
            $table->string('status')->default('active');
            $table->float('deposit')->default('0');
            $table->double('rating')->default('0');
            $table->json('services')->nullable();
            $table->json('ar_services')->nullable();
            $table->boolean('isFeatured')->default(false);
            $table->timestamps();




            $table->index('cuisine_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Restaurants');
    }
};
