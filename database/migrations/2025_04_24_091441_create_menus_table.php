// database/migrations/2025_04_24_091441_create_menus_table.php
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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            // Kolom-kolom 'category' dan 'is_active' ditambahkan di migrasi terpisah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // <--- PASTIKAN ADA ": void" di sini
    {
        Schema::dropIfExists('menus');
        // Tidak perlu ada return statement di sini
    }
};
