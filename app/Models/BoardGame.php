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

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
