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
        Schema::create('notifications', function (Blueprint $table) {
             $table->id();
            $table->string('title') ;
            $table->text('description');
            $table->string('ar_title') ;
            $table->text('ar_description');
            $table->date('date');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->onUpdate('cascade') ;
            $table->index('customer_id');
            $table->boolean('seen_type')->default(false) ;
            $table->boolean('live_type')->default(false) ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
