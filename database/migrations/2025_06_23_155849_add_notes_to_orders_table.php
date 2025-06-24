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
                // Tambahkan kolom 'notes' sebagai tipe text dan bisa null (opsional)
                // dan ditempatkan setelah kolom 'table_number' atau sebelum 'total_amount'.
                // Saya akan letakkan setelah payment_method untuk konsistensi dengan order-controller.
                // Jika total_amount belum ada, atau Anda ingin menempatkannya di akhir, bisa juga.
                // Mengacu pada OrderController, 'notes' ada sebelum 'status' dalam list insert.
                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('table_number'); // Posisi bisa disesuaikan
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('orders', function (Blueprint $table) {
                // Hapus kolom 'notes' jika rollback migrasi
                if (Schema::hasColumn('orders', 'notes')) {
                    $table->dropColumn('notes');
                }
            });
        }
    };
