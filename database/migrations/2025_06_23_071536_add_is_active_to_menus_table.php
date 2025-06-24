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
        Schema::table('menus', function (Blueprint $table) {
            // Tambahkan kolom 'is_active' dengan tipe boolean.
            // Defaultnya adalah true (aktif) dan ditempatkan setelah kolom 'image'.
            if (!Schema::hasColumn('menus', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Hapus kolom 'is_active' jika rollback migrasi
            if (Schema::hasColumn('menus', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
