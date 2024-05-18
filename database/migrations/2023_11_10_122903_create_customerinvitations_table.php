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
        Schema::create('customerinvitations', function (Blueprint $table) {
            $table->id();
            $table->string('expire');//num days
            $table->string('discount');
            $table->string('title');
            $table->string('type');
            $table->integer('target');  //invitations reviews reservations
            $table->string('description');
            $table->string('coupons');
            $table->string('limit');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customerinvitations');
    }
};
