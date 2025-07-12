<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'type'];

    public function menus()
    {
        return $this->hasMany(Menu::class); // default: foreign key = category_id
    }
}
