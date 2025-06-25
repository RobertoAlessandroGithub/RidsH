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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name'); // nama customer
            $table->string('customer_phone')->nullable(); // nomor telepon, boleh kosong
            $table->decimal('total_amount', 10, 2)->default(0); // total harga
            $table->string('status')->default('pending'); // status order
            $table->string('table_number')->nullable();
            $table->text('notes')->nullable()->after('table_number');
            $table->string('payment_method')->nullable()->after('status');
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
