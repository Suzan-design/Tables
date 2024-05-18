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
        Schema::table('tables', function (Blueprint $table) {
            // Drop the old unique constraint on 'number'
            $table->dropUnique(['number']);

            // Add a new composite unique index on 'number' and 'Restaurant_id'
            $table->unique(['number', 'Restaurant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            // Drop the composite unique index
            $table->dropUnique(['number', 'Restaurant_id']);

            // Add back the original unique constraint on 'number'
            $table->unique('number');
        });
    }
};
