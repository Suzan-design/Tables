<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tablereservations', function (Blueprint $table) {
            $table->integer('children')->nullable();
            $table->integer('adult')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tablereservations', function (Blueprint $table) {
            $table->dropColumn('children');
            $table->dropColumn('adult');
        });
    }
};
