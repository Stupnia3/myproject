<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['name', 'photo', 'bio'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_teacher');
    }
}
