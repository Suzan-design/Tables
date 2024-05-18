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
        Schema::create('tablereservations', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('mtn_invoice_id')->unique()->nullable();
            $table->unsignedBigInteger('invoice_id')->unique()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->bigInteger('table_id')->unsigned()->nullable();
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');

            $table->bigInteger('Restaurant_id')->unsigned();
            $table->foreign('Restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->time('request_reservation_time')->nullable();
            $table->string('speacial_request')->nullable();
            $table->string('actual_price')->nullable();
            $table->string('reservation_time'); //(9\12\2023 4:32 PM)
            $table->string('reservation_time_end')->nullable(); //(9\12\2023 4:32 PM)
            $table->string('duration')->nullable(); //(9\12\2023 4:32 PM)
            $table->date('reservation_date'); //(9\12\2023 4:32 PM)
            $table->string('party_size')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('promocode')->nullable();

            $table->string('first_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('last_name')->nullable();

            $table->string('status')->default('scheduled');   //status(scheduled - current - next - done - deleted)
            $table->timestamps();

            $table->index('Restaurant_id');
            $table->index('table_id');
        });
    }


    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('tablereservations');
    }
};
