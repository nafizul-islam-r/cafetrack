<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

// Add these two lines
use App\Models\User;
use App\Models\FoodItem;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'food_item_id',
        'rating',
        'comment',
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