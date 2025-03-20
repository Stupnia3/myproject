<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'practical_parts',
        'methodologies',
        'photo',
        'start_date',
        'end_date',
        'total_seats',
        'occupied_seats',
    ];

    protected $casts = [
        'practical_parts' => 'array', // JSON как массив
        'methodologies' => 'array',   // JSON как массив
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
