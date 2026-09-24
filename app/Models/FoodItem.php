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

    public function getImageUrlAttribute($value)
    {
        $fallback = 'https://placehold.co/600x400/EEE3C3/7C4A19?text=Food+Image';

        if (empty($value) || !filter_var($value, FILTER_VALIDATE_URL)) {
            return $fallback;
        }

        return $value;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
