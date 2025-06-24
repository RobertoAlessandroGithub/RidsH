<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use App\Models\Category; // Pastikan ini di-import

    class Menu extends Model
    {
        use HasFactory;

        protected $fillable = [
            'name',
            'slug',
            'category_id', // Pastikan ini ada di fillable
            'description',
            'detailed_description', // Pastikan ini juga ada jika Anda menggunakannya
            'price',
            'image',
            'is_active',
        ];

        // Definisikan relasi 'belongsTo' ke model Category
        public function category()
        {
            return $this->belongsTo(Category::class);
        }
    }
