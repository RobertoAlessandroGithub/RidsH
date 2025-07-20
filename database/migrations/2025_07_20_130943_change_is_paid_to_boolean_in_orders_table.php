<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this line if not already present

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Change column to boolean, ensuring default is false and allowing null if needed
            // If you are absolutely certain it should never be null, remove ->nullable()
            $table->boolean('is_paid')->default(false)->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to original type if needed, for example, a tinyInteger
            // Adjust this based on what your 'is_paid' column was before
            $table->tinyInteger('is_paid')->default(0)->change();
        });
    }
};