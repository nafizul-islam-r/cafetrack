<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardGame extends Model
{
    protected $fillable = [
        'name',
        'total_units',
        'available_units',
        'image_url',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
