<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'food_item_id',
        'food_item_name',
        'food_item_image_url',
        'food_item_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }
}
