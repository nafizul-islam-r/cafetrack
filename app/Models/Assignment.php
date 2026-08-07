<?php

namespace App\Models;

use App\Models\User;
use App\Models\BoardGame;
use MongoDB\Laravel\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'user_id',
        'board_game_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boardGame()
    {
        return $this->belongsTo(BoardGame::class);
    }
}
