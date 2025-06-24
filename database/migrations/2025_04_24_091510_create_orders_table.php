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
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                // Foreign key ke tabel 'orders'
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                // Foreign key ke tabel 'menus'
                $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
                $table->integer('quantity'); // Jumlah item menu yang dipesan
                $table->decimal('price', 10, 2); // Harga menu saat dipesan (penting untuk histori)
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('order_items');
        }
    };
