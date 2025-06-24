<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Category extends Model
    {
        use HasFactory;

        protected $fillable = [
            'name',
            'slug', // Slug biasanya digunakan untuk URL yang rapi
        ];

        /**
         * Get the menus for the category.
         */
        public function menus()
        {
            return $this->hasMany(Menu::class, 'category', 'name'); // Jika kolom category di Menu menyimpan nama kategori
            // Atau jika category di Menu menyimpan ID kategori:
            // return $this->hasMany(Menu::class);
        }
    }
