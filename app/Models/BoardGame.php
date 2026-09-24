<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class BoardGame extends Model
{
    protected $fillable = [
        'name',
        'total_units',
        'available_units',
        'image_url',
    ];

    protected $casts = [
        'total_units' => 'integer',
        'available_units' => 'integer',
    ];

    public function getImageUrlAttribute($value)
    {
        $fallback = 'https://placehold.co/600x400/EEE3C3/7C4A19?text=Board+Game';

        if (empty($value) || !filter_var($value, FILTER_VALIDATE_URL)) {
            return $fallback;
        }

        return $value;
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
