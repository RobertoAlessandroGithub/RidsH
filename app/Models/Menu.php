<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image', // Jika hanya menyimpan satu gambar sebagai string path
        // 'images', // Jika Anda punya kolom 'images' untuk menyimpan array JSON dari path gambar
        // 'category_id', // Jika Anda punya kolom untuk kategori
        'category', // Tambahkan jika ada kolom ini di DB
        // 'average_rating',
        // 'total_ratings',
        // 'reviews',
        // 'nutritional_info',
    ];

    // PENTING: Jika Anda menyimpan array gambar, reviews, dll. sebagai JSON di database,
    // Anda perlu menambahkan casting ini.
    protected $casts = [
        'images' => 'array', // Jika kolom 'images' menyimpan JSON array of image paths
        'reviews' => 'array', // Jika kolom 'reviews' menyimpan JSON array of review objects
        // 'nutritional_info' => 'array', // Jika ini juga JSON
    ];
}
