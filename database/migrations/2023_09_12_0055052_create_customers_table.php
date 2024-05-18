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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('phone');
            $table->string('invitationCode')->nullable();
            $table->integer('numberOfInvitations')->default('0');
            $table->integer('numberOfReviews')->default('0');
            $table->integer('numberOfReservations')->default('0');
            $table->string('gender')->nullable();
            $table->string('State')->nullable();
            $table->boolean('allowNotification')->default('0');
            $table->boolean('isVerified')->default('0');
            $table->boolean('isComplete')->default('0');
            $table->string('profilePicture')->nullable();
            $table->date('birthDate')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->json('followed_restaurants')->nullable();
            $table->json('coordinates')->nullable();
            $table->json('promocodes')->nullable();
            $table->json('userOfInvitations')->nullable();
            $table->boolean('isBlocked')->default('0');
            $table->rememberToken();
            $table->timestamps();


            $table->index('numberOfInvitations');
            $table->index('numberOfReviews');
            $table->index('numberOfReservations');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
