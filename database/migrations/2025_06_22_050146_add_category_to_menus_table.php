<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Hanya tambahkan kolom 'category' jika belum ada
            if (!Schema::hasColumn('menus', 'category')) {
                $table->string('category')->nullable()->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Hapus kolom 'category' jika ada
            if (Schema::hasColumn('menus', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
