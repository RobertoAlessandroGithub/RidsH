<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use Illuminate\Http\Request;

class Menu extends Model
{
    use HasFactory;

    // Menentukan atribut yang dapat diisi (mass assignable)
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    // Jika Anda memiliki relasi, Anda bisa mendefinisikannya di sini
    // Contoh: public function orders() { return $this->hasMany(Order::class); }
}