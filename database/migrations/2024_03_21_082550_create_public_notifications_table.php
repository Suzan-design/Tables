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
        Schema::create('public_notifications', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description');
            $table->string('target_ages')->nullable();
            $table->string('target_states')->nullable();
            $table->string('target_reservations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_notifications');
    }
};
