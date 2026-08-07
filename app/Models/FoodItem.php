<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FoodItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'image_url',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
