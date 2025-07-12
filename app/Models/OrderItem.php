<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class OrderItem extends Model
    {
        use HasFactory;

        protected $fillable = [
            'order_id',
            'menu_id',
            'quantity',
            'price',
        ];

        /**
         * Get the order that owns the order item.
         */
        public function order()
        {
            return $this->belongsTo(Order::class);
        }

        /**
         * Get the menu that owns the order item.
         */
        public function menu()
        {
            return $this->belongsTo(Menu::class);
        }
    }
