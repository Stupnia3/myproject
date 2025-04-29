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
        'tags',
        'location',
        'duration',
    ];

    protected $casts = [
        'practical_parts' => 'array',
        'methodologies' => 'array',
        'tags' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->total_seats - $this->occupied_seats);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'event_teacher');
    }
}
