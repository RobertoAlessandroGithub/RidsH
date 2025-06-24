<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Order extends Model
    {
        use HasFactory;

        protected $fillable = [
            'customer_name',
            'customer_phone',
            'table_number',
            'total_amount',
            'status',
            'notes',
            'payment_method',
        ];

        /**
         * Get the order items for the order.
         */
        public function items()
        {
            return $this->hasMany(OrderItem::class);
        }
    }
