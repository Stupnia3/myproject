<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'text', 'rating', 'image'];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
