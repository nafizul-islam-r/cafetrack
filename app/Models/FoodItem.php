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

    protected $casts = [
        'stock_quantity' => 'integer',
        'price' => 'float',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
