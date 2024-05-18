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
        Schema::create('promotionalcodes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('discount');
            $table->string('limit');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('image')->nullable();
            $table->boolean('is_reservaions');
            $table->boolean('is_followings');
            $table->string('num_us');
            $table->string('num_res');
            $table->string('description')->nullable();
            $table->enum('type', array('invitation','promotion'))->default('promotion');
            $table->string('status')->default('active');
            $table->json('filter_us')->nullable();
            $table->json('filter_res')->nullable();
            $table->json('users_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotionalcodes');
    }
};
