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
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->startingValue(1000000800) ;
            $table->bigInteger('invoice_id')->unique();
            $table->string('operation_number')->nullable() ;
            $table->bigInteger('external_id')->unique() ;

            $table->enum('status' , ['pending' , 'paid'])->default('pending') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
