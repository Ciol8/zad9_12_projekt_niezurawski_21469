<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- WAŻNE
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'kcal_per_100g', 'brand_id', 'category_id'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function allergens()
    {
        return $this->belongsToMany(Allergen::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}