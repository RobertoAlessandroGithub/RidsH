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
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan index pada kolom yang sering difilter
            $table->index('status');
            $table->index('created_at');
            $table->index('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Perintah untuk menghapus index jika migrasi di-rollback
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['customer_name']);
        });
    }
};
