<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'name',
        'table_number',
        'total_price',
        'items',
        'status',
    ];

    protected $casts = [
        'items' => 'array',  // Untuk memastikan 'items' disimpan sebagai array atau JSON
    ];
}
